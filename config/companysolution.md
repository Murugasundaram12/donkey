# Production-Ready API Authentication Solution for Donkey App

## Architecture: API Key via Header (Recommended)

### Why This Approach:

1. **Never expose `company_id` or `company_code` in URL/body** — they are internal identifiers
2. **API Key = Opaque token** — 64-char SHA256 hash, unguessable, revocable per company
3. **Single Header `X-API-Key`** — standard, simple, cache-friendly
4. **Middleware intercepts before controller** — clean separation of concerns

---

## What's Already Built

### 1. Database Migration ✅

-   Added `api_key` column (64-char, unique, nullable) to `companies` table
-   Auto-generated for all existing companies via migration

### 2. Model Updated ✅

-   `Company::generateApiKey()` — creates SHA256 hash
-   Auto-assigns on company creation via `boot()`

### 3. Middleware Created ✅

`app/Http/Middleware/EnsureApiKeyIsValid.php`

-   Reads `X-API-Key` header
-   Looks up company (must be active)
-   Injects `company` and `company_id` into request
-   Returns 401 if invalid/missing

### 4. Next Steps (Manual)

#### Step A: Register Middleware in Kernel

File: `app/Http/Kernel.php`
Add to `$routeMiddleware` array:

```php
'company.api' => \App\Http\Middleware\EnsureApiKeyIsValid::class,
```

#### Step B: Apply to API Routes

File: `routes/api.php`

```php
// Company-protected routes (external app bookings)
Route::middleware('company.api')->group(function () {
    Route::post('/booking/calculation', [\App\Http\Controllers\API\otherController::class, 'bookingCalculation']);
    Route::post("bookingtaxi", [\App\Http\Controllers\API\otherController::class, "bookingtaxi"]);
});

// Public routes (no company auth needed)
Route::post('/register', [App\Http\Controllers\API\RegisterController::class, 'register']);
Route::post('/login', [App\Http\Controllers\API\RegisterController::class, 'login']);
```

#### Step C: Modify Controller Methods

For external booking methods, remove `company_id` from validation and read from header:

```php
// In otherController::bookingCalculation()
public function bookingCalculation(Request $request)
{
    // No need to validate company_id from body — middleware handles it
    $companyId = $request->get('company_id'); // Injected by middleware

    $validator = Validator::make($request->all(), [
        'source' => 'required|in:0,1',
        'user_id' => 'required_if:source,0|nullable|string|exists:users,user_id',
        'external_phone' => 'required_if:source,1|nullable|string',
        // 'company_id' => 'required_if:source,1|integer|exists:companies,id', // REMOVE THIS
        'distance' => 'required|numeric|min:0',
        'pincode' => 'required|digits:6|exists:pincode,pincode',
        'category' => 'required|integer|in:1,2,3,4,5'
    ]);
}

// In otherController::bookingtaxi()
$company_id = $request->get('company_id'); // From middleware, null if not present
```

---

## Sample API Request

### External App Making a Booking

```bash
curl -X POST https://yourdomain.com/api/bookingtaxi \
  -H "Content-Type: application/json" \
  -H "X-API-Key: dk_live_a1b2c3d4e5f6..." \
  -d '{
    "source": 1,
    "external_phone": "+919876543210",
    "external_name": "John Doe",
    "distance": 5.2,
    "pincode": "600001",
    "category": 1,
    "description": "Office pickup",
    "from_location": {
      "address1": "123 Main St",
      "city": "Chennai",
      "state": "TN",
      "lat": "13.0827",
      "long": "80.2707"
    },
    "to_location": [{
      "address1": "456 Park Ave",
      "city": "Chennai",
      "state": "TN",
      "lat": "13.0569",
      "long": "80.2425"
    }],
    "payment_method": "online"
  }'
```

### Response (Success)

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

### Response (Invalid Key)

```json
{
    "success": false,
    "message": "Unauthorized: Invalid or inactive API key."
}
```

---

## Admin: Viewing & Regenerating API Keys

Add this method to `app/Http/Controllers/Admin/CompanyController.php`:

```php
/**
 * Regenerate API key for a company
 */
public function regenerateApiKey(Company $company)
{
    $company->api_key = Company::generateApiKey();
    $company->save();

    return redirect()->back()
        ->with('success', 'API key regenerated successfully. New key: ' . substr($company->api_key, 0, 12) . '...');
}
```

And in `show.blade.php`, display the API key (masked):

```html
<!-- Show API Key (masked) -->
<tr>
    <th class="text-muted">API Key</th>
    <td>
        <code>{{ substr($company->api_key, 0, 12) }}************</code>
        <span class="text-muted ml-2"
            >(Last 4: {{ substr($company->api_key, -4) }})</span
        >
    </td>
</tr>
```

---

## Comparison Table

| Method                  | Security               | Scalability | Ease of Use | Recommendation     |
| ----------------------- | ---------------------- | ----------- | ----------- | ------------------ |
| company_id in header    | ❌ Internal ID exposed | ⚠️ Medium   | ✅ Easy     | Not recommended    |
| company_code in header  | ⚠️ Guessable           | ⚠️ Medium   | ✅ Easy     | Not recommended    |
| X-Company-Code header   | ⚠️ Same as above       | ⚠️ Medium   | ✅ Easy     | Not recommended    |
| **API Key (X-API-Key)** | ✅ Opaque, unguessable | ✅ High     | ✅ Easy     | **✅ Recommended** |
| API Key + IP whitelist  | ✅ Highest             | ⚠️ Medium   | ❌ Complex  | Optional extra     |
| OAuth2 / JWT            | ✅ Very high           | ✅ High     | ❌ Complex  | Overkill for now   |

---

## Security Best Practices

1. **Rotate keys periodically** — Admin can regenerate via UI
2. **Log API usage** — Track which company made which calls
3. **Rate limiting** — Per-company rate limits (Laravel Throttle)
4. **Never expose full key in UI** — Show only first/last few characters
5. **HTTPS only** — API keys in plaintext over HTTP = compromised
6. **Inactive companies = 401** — Disable access without deleting data

---

## How It Works (Flow)

```
External App                    Donkey API
    |                               |
    |--- POST /api/bookingtaxi --->|
    |    Headers:                   |
    |    X-API-Key: dk_live_...     |
    |                               |
    |                    Middleware:|
    |                    1. Extract |
    |                       X-API-Key|
    |                    2. Lookup  |
    |                       company |
    |                    3. Inject  |
    |                       company_id|
    |                    4. Pass to |
    |                       controller|
    |                               |
    |<--- JSON Response ----------|
         With tracking by company
```

This is a secure, scalable, production-ready solution that integrates cleanly with your existing Companies module and booking flow.
