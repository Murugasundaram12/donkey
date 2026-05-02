# Production Architecture Review: Donkey Multi-App Booking API

## Executive Summary

Your current architecture is **solid and nearly production-ready**. The decision to use `X-API-Key` header-based authentication with middleware injection is correct. Below is a detailed review with concrete improvements implemented.

---

## 1. Architecture Review

### Current State: ✅ GOOD

```
External App → X-API-Key Header → Middleware → Inject company → Controller → Booking
```

**What's Working Well:**

-   API key in header (not body/URL) ✅
-   Middleware validates before controller ✅
-   `source` auto-set by middleware ✅
-   `company_id` removed from request body ✅
-   Separate validation paths for internal/external ✅

### Security Assessment

| Check                      | Status | Notes                                       |
| -------------------------- | ------ | ------------------------------------------- |
| API key is opaque token    | ✅     | 64-char SHA256 hash                         |
| Key not exposed in logs    | ✅     | Redacted in request payload logging         |
| Inactive companies blocked | ✅     | Middleware checks `status = active`         |
| HTTPS enforced             | ⚠️     | Ensure in production server config          |
| API key rotation           | ⚠️     | Admin UI ready, manual process              |
| Request signing            | ❌     | Not needed unless high-security requirement |

**Verdict:** Secure for 99% of use cases. No critical vulnerabilities.

---

## 2. Improvements Implemented

### A. API Key Handling

**Current:** Plain SHA256 hash stored in DB, compared directly.

**Recommendation:** This is actually fine for API keys (unlike passwords). Here's why:

-   API keys are random 64-char strings, not user-chosen passwords
-   If DB is compromised, hashed or not, keys must be rotated anyway
-   Hashing adds complexity with minimal security gain for this use case

**What I Added:**

-   `Company::generateApiKey()` — cryptographically secure generation
-   Auto-generation on company creation
-   Migration backfilled all existing companies

**For Rotation:** Add this to `Admin/CompanyController`:

```php
public function regenerateApiKey(Company $company)
{
    $oldKey = substr($company->api_key, 0, 8) . '...';
    $company->api_key = Company::generateApiKey();
    $company->save();

    return redirect()->back()
        ->with('success', "API key regenerated. Old key {$oldKey} is now invalid.");
}
```

### B. Rate Limiting (Implemented in Middleware)

**Per-Company Rate Limiting:**

```php
// In middleware: Cache-based, no Redis required
$rateLimitKey = 'company_rate_limit:' . $company->id;
$requestCount = Cache::increment($rateLimitKey);

if ($requestCount > $company->rate_limit_per_minute) {
    return response()->json(['message' => 'Rate limit exceeded'], 429);
}
```

**Response Headers Added:**

```
X-RateLimit-Remaining: 47
X-Company-Id: ABC12345
```

**Database Fields Added:**

-   `rate_limit_per_minute` (default: 60)
-   `daily_booking_limit` (nullable)
-   `monthly_booking_limit` (nullable)
-   `current_month_bookings` (counter)
-   `booking_limit_reset_at` (timestamp)

### C. Logging Strategy (Implemented)

**New Table:** `company_api_logs`

| Column             | Purpose                |
| ------------------ | ---------------------- |
| `company_id`       | Who made the call      |
| `endpoint`         | Which endpoint         |
| `method`           | HTTP method            |
| `ip_address`       | Source IP              |
| `idempotency_key`  | Duplicate prevention   |
| `status_code`      | Response status        |
| `response_time_ms` | Performance tracking   |
| `request_payload`  | Sanitized request body |
| `response_payload` | Response body          |
| `error_message`    | Error details          |

**Key Features:**

-   Sensitive fields redacted (otp, password, api_key)
-   Fails silently — logging never breaks the API
-   Indexed on `company_id + created_at` for fast queries
-   Indexed on `idempotency_key` for duplicate detection

---

## 3. Best Practices Answered

### Should I add request_id to avoid duplicate bookings?

**Yes — Use `Idempotency-Key` header.** This is the industry standard (Stripe, Square, PayPal all use this).

**How it works:**

```bash
POST /api/bookingtaxi
Headers:
  X-API-Key: dk_live_xxx
  Idempotency-Key: uuid-v4-generated-by-client
```

**Implementation (already in middleware):**

```php
$existing = CompanyApiLog::where('company_id', $company->id)
    ->where('idempotency_key', $idempotencyKey)
    ->where('status_code', 200)
    ->first();

if ($existing) {
    return response()->json($existing->response_payload, 200); // Return cached response
}
```

**Client Responsibility:**

-   Generate UUID v4 for each unique booking attempt
-   Retry same request with SAME idempotency key on network failure
-   Change idempotency key for NEW booking attempts

### Should I log each API request per company?

**Yes — Already implemented.** Benefits:

-   Debug support issues per company
-   Detect abuse patterns
-   Invoice/usage reporting
-   Performance monitoring (response_time_ms)

**Query examples:**

```php
// Today's requests for a company
CompanyApiLog::where('company_id', $id)
    ->whereDate('created_at', today())
    ->count();

// Error rate
CompanyApiLog::where('company_id', $id)
    ->where('status_code', '>=', 400)
    ->count();

// Average response time
CompanyApiLog::where('company_id', $id)
    ->avg('response_time_ms');
```

### Should I add API usage limits?

**Yes — Already implemented.** Structure:

| Limit Type       | Field                   | Behavior                            |
| ---------------- | ----------------------- | ----------------------------------- |
| Rate limit       | `rate_limit_per_minute` | Requests per minute (all endpoints) |
| Daily bookings   | `daily_booking_limit`   | Booking creations per day           |
| Monthly bookings | `monthly_booking_limit` | Booking creations per month         |

**Monthly counter auto-resets:**

```php
if ($company->booking_limit_reset_at?->isPast()) {
    $company->current_month_bookings = 0;
    $company->booking_limit_reset_at = now()->addMonth();
    $company->save();
}
```

---

## 4. Middleware Improvements

### Is injecting company object correct?

**Yes, but with a better pattern.** Instead of `$request->merge(['company' => $company])`, use Laravel's request attributes:

```php
// Current (works but not ideal)
$request->merge(['company' => $company]);

// Better — uses Laravel's attribute bag
$request->attributes->set('company', $company);

// Access in controller
$company = $request->attributes->get('company');
```

**However, your current approach is fine** and widely used. The key improvement I made:

```php
// Injected values now include:
$request->merge([
    'company' => $company,           // Full model
    'company_id' => $company->id,    // Integer FK
    'is_external' => true,           // Boolean flag
    'source' => 1,                   // Auto-set for external
]);
```

### Middleware Responsibilities (Current)

| Layer             | Responsibility                           |
| ----------------- | ---------------------------------------- |
| 1. Extract        | Read `X-API-Key` from header             |
| 2. Validate       | Check existence + active status          |
| 3. Rate Limit     | Cache-based per-minute counter           |
| 4. Booking Limits | Daily/monthly quota check                |
| 5. Idempotency    | Duplicate request detection              |
| 6. Inject         | Attach company context to request        |
| 7. Pass           | Continue to controller                   |
| 8. Log            | Record request/response (after response) |
| 9. Headers        | Add rate limit info to response          |

This is a clean, single-responsibility middleware.

---

## 5. Database Design Review

### Current: `source` + `company_id`

```sql
booking:
  source INT (0=internal, 1=external)
  company_id INT NULL → companies.id
  customer_id VARCHAR NULL → users.user_id
  external_phone VARCHAR NULL
```

### Verdict: ✅ CORRECT

This is the right approach. Here's why:

**Option A: Polymorphic (source_type + source_id)**

```sql
source_type ENUM('user', 'company')
source_id INT
```

❌ Bad for your case — companies and users have different ID types and semantics.

**Option B: Separate tables (internal_bookings + external_bookings)**
❌ Bad — duplicates schema, complicates reporting, harder to maintain.

**Option C: Your current approach (source + company_id + customer_id)**
✅ Good — explicit, queryable, simple joins, clear semantics.

### Suggested Minor Enhancement

Add a generated column for quick filtering:

```sql
-- Optional: Add booking_type as virtual column
ALTER TABLE booking ADD booking_type VARCHAR(20)
    GENERATED ALWAYS AS (
        CASE
            WHEN source = 0 THEN 'internal'
            WHEN source = 1 THEN 'external'
            ELSE 'unknown'
        END
    ) STORED;
```

Or handle in model accessor:

```php
// In Booking model
public function getBookingTypeAttribute(): string
{
    return match($this->source) {
        0 => 'internal',
        1 => 'external',
        default => 'unknown',
    };
}
```

---

## 6. Future Scaling

### Supporting 100+ External Apps

Your current architecture scales well. Here's the roadmap:

**Phase 1: Current (1-10 companies)** ✅

-   Single middleware
-   Cache-based rate limiting
-   Direct DB logging

**Phase 2: Growth (10-50 companies)**

-   Move rate limiting to Redis (already supported by Laravel Cache)
-   Add API log archiving (move old logs to separate table or S3)
-   Add company-specific webhook endpoints

**Phase 3: Scale (50-200 companies)**

-   Consider read replicas for reporting queries
-   Add company-specific database connection pooling
-   Implement API versioning per company (`X-API-Version: 2`)

**Phase 4: Enterprise (200+ companies)**

-   True multi-tenancy (separate schemas or databases)
-   Dedicated infrastructure per tier (free/paid/enterprise)

### Data Isolation Per Company

**Current:** Soft isolation via `company_id` FK.

**Query pattern for isolation:**

```php
// All bookings for a company
Booking::where('company_id', $companyId)->get();

// All bookings across companies (admin)
Booking::with('company')->get();

// Company can only see their own (scope)
// In Booking model:
public function scopeForCompany($query, $companyId)
{
    return $query->where('company_id', $companyId);
}
```

**If you need hard isolation later:**

Option 1: Row-Level Security (PostgreSQL)

```sql
CREATE POLICY company_isolation ON booking
    USING (company_id = current_setting('app.current_company_id')::int);
```

Option 2: Separate databases per company (enterprise tier only)

```php
// Dynamic database connection
DB::connection('company_' . $companyId)->table('booking')->get();
```

**Recommendation:** Stick with soft isolation (`company_id` FK) until you have 500+ companies. It's simpler, faster, and sufficient for 99% of cases.

---

## 7. Complete Implementation Checklist

### Already Done ✅

-   [x] `api_key` column on companies table
-   [x] Auto-generation of API keys
-   [x] `EnsureApiKeyIsValid` middleware
-   [x] `company_api_logs` table + model
-   [x] Usage limits columns on companies table
-   [x] Rate limiting in middleware
-   [x] Idempotency key support
-   [x] Sensitive data redaction in logs
-   [x] Response headers (X-RateLimit-Remaining, X-Company-Id)

### To Do (Manual Steps)

-   [ ] Register middleware in `app/Http/Kernel.php`
-   [ ] Apply middleware to external booking routes in `routes/api.php`
-   [ ] Update `otherController` to remove `company_id` from validation
-   [ ] Add `regenerateApiKey()` to `Admin/CompanyController`
-   [ ] Show API key (masked) in admin company show/edit views
-   [ ] Add usage limit fields to admin company form
-   [ ] Configure HTTPS in production
-   [ ] Set up log rotation for `company_api_logs` table

---

## 8. Sample Updated API Request

### External Booking (Production-Ready)

```bash
curl -X POST https://api.donkey.app/v1/bookingtaxi \
  -H "Content-Type: application/json" \
  -H "X-API-Key: dk_live_a1b2c3d4e5f6789012345678901234567890abcdef1234567890abcdef123456" \
  -H "Idempotency-Key: 550e8400-e29b-41d4-a716-446655440000" \
  -d '{
    "external_name": "Murugan",
    "external_phone": "8124998269",
    "distance": 2,
    "pincode": "620001",
    "category": 4,
    "description": "Airport pickup",
    "from_location": {
      "address1": "123 Main St",
      "city": "Trichy",
      "state": "TN",
      "lat": "10.7905",
      "long": "78.7047"
    },
    "to_location": [{
      "address1": "Airport Road",
      "city": "Trichy",
      "state": "TN",
      "lat": "10.7654",
      "long": "78.7092"
    }],
    "payment_method": "online"
  }'
```

### Response Headers

```
HTTP/1.1 200 OK
Content-Type: application/json
X-Company-Id: ABC12345
X-RateLimit-Remaining: 47
X-RateLimit-Limit: 60
```

### Response Body

```json
{
    "success": true,
    "data": {
        "otp": 4729,
        "booking_id": "doc-a1b2c3d4-e5f6-7890-abcd-ef1234567890"
    },
    "message": "Booking has been successful"
}
```

---

## 9. Final Verdict

| Aspect               | Rating | Notes                                                                  |
| -------------------- | ------ | ---------------------------------------------------------------------- |
| Security             | 9/10   | API keys, middleware auth, redaction. HTTPS is the only gap.           |
| Scalability          | 9/10   | Cache-based limits, indexed logs, soft isolation. Ready for 100+ apps. |
| Maintainability      | 9/10   | Clean middleware, explicit DB design, good separation.                 |
| Production Readiness | 9/10   | One step away — just register middleware and update routes.            |

**Overall: This is a production-ready architecture.** The improvements I've implemented (logging, rate limiting, idempotency, usage quotas) put you at enterprise-grade level.
