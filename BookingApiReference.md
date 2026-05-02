# Donkey App — External Booking API Reference

> **Base URL:** `https://your-domain.com/api`  
> **Response Format:** All responses follow the standard envelope below.

```json
{
    "success": true,
    "message": "string",
    "data": {}
}
```

---

## Authentication

External endpoints accept an **API Key** to identify your company.

| Header    | Value          | Required |
| --------- | -------------- | -------- |
| X-API-Key | `your-api-key` | Yes\*    |

> -   Required for third-party / external integrations. The Donkey mobile app may call some shared endpoints **without** this header for internal users (`source=0`).

---

## API 1: Retrieve Booking History

| Property         | Value                      |
| ---------------- | -------------------------- |
| **Endpoint**     | `POST /api/bookinghistory` |
| **Method**       | POST                       |
| **Content-Type** | `application/json`         |

### Headers

| Header       | Value              | Required |
| ------------ | ------------------ | -------- |
| Content-Type | `application/json` | Yes      |
| X-API-Key    | `your-api-key`     | Yes      |
| Accept       | `application/json` | No       |

> -   For external usage, always send `X-API-Key`.

### Request Body

**External user (source=1):**

```json
{
    "source": 1,
    "external_phone": "+918888888888"
}
```

**Internal user (source=0):**

```json
{
    "source": 0,
    "user_id": "DK-a1b2c3d4"
}
```

### Validation Rules

| Field            | Rules                           | Condition                |
| ---------------- | ------------------------------- | ------------------------ |
| `source`         | `required`, `integer`, `in:0,1` | Always                   |
| `user_id`        | `required_if:source,0`          | Required when `source=0` |
| `external_phone` | `required_if:source,1`          | Required when `source=1` |

### Field Explanation

| Field            | Type      | Description                                                          |
| ---------------- | --------- | -------------------------------------------------------------------- |
| `source`         | `integer` | `0` = Donkey app user (internal). `1` = External / third-party.      |
| `user_id`        | `string`  | Unique Donkey user ID (e.g. `DK-xxxxxxxx`). Required for `source=0`. |
| `external_phone` | `string`  | Customer phone number. Required for `source=1`.                      |

### Notes

-   When `source=0`, bookings are located by `customer_id = user_id`.
-   When `source=1`, bookings are located by `external_phone`.
-   Results are sorted by `created_at` descending (newest first).
-   If no bookings exist, returns `success: true` with empty `data` array `[]`.

### ✅ Success Response — `200 OK`

```json
{
    "success": true,
    "message": "Booking History",
    "data": [
        {
            "user": {
                "name": "Rahul Sharma",
                "email": "rahul@example.com",
                "phone": "+918888888888",
                "user_id": "DK-abc123",
                "image": "https://your-domain.com/public/userprofile/rahul.jpg"
            },
            "bookingdetails": {
                "booking_id": "doc-a1b2c3d4-e5f6-7890-abcd-ef1234567890",
                "customer_id": "DK-abc123",
                "category": 4,
                "distance": 12.5,
                "status": 2,
                "created_at": "2025-01-15T10:30:00.000000Z",
                "booking_status": "Booking Has Been Completed",
                "time": "10:30 AM"
            },
            "starting_location": {
                "address1": "123 Main Street",
                "address2": "Near Metro Station",
                "city": "Chennai",
                "state": "Tamil Nadu",
                "postal_code": "600001",
                "lat": "13.0827",
                "long": "80.2707"
            },
            "ending_location": {
                "address1": "456 Central Plaza",
                "city": "Chennai",
                "state": "Tamil Nadu",
                "postal_code": "600002"
            },
            "price": {
                "total": 285.6,
                "roundofftotal": 286,
                "base_price": 225.0,
                "tax": 40.6,
                "tax_split_1": 20.3,
                "tax_split_2": 20.3,
                "coupon_id": null,
                "coupon_amount": null
            },
            "booked_at": "2025-01-15T10:30:00.000000Z",
            "distance": 13
        }
    ]
}
```

### ❌ Validation Error — `422 Unprocessable Entity`

```json
{
    "success": false,
    "message": "Validation Error.",
    "data": {
        "source": ["The source field is required."],
        "external_phone": [
            "The external phone field is required when source is 1."
        ]
    }
}
```

### ❌ Application Error — `200 OK`

```json
{
    "success": false,
    "message": "User not found",
    "data": {}
}
```

---

## API 2: Retrieve Booking Details by ID

| Property         | Value                             |
| ---------------- | --------------------------------- |
| **Endpoint**     | `POST /api/getbookingdetailsofid` |
| **Method**       | POST                              |
| **Content-Type** | `application/json`                |

### Headers

| Header       | Value              | Required |
| ------------ | ------------------ | -------- |
| Content-Type | `application/json` | Yes      |
| X-API-Key    | `your-api-key`     | Yes      |
| Accept       | `application/json` | No       |

> -   For external usage, always send `X-API-Key`.

### Request Body

```json
{
    "booking_id": "doc-a1b2c3d4-e5f6-7890-abcd-ef1234567890"
}
```

### Validation Rules

| Field        | Rules                | Condition |
| ------------ | -------------------- | --------- |
| `booking_id` | `required`, `string` | Always    |

### Field Explanation

| Field        | Type     | Description                                        |
| ------------ | -------- | -------------------------------------------------- |
| `booking_id` | `string` | Unique booking identifier (format: `doc-xxxxxxxx`) |

### Notes

-   Returns **single** booking detail record.
-   Includes: user profile, start & end locations, payment breakdown, distance.
-   `coupon_id` and `coupon_amount` will be `null` if no coupon was applied.
-   `roundofftotal` is the rounded value of `total` for display.
-   `booking_status` is a **human-readable string** derived from the numeric `status` field.
-   **Status mapping:**
    -   `0` → `Searching For The Driver`
    -   `1` → `Driver Has Accepted the Order And He is On The Way`
    -   `2` → `Booking Has Been Completed`
    -   `3` → `Booking has Been Cancelled`
    -   `4` → `Your Ride Has Started`

### ✅ Success Response — `200 OK`

```json
{
    "success": true,
    "message": "Booking Details",
    "data": {
        "user": {
            "name": "Rahul Sharma",
            "email": "rahul@example.com",
            "phone": "+918888888888",
            "user_id": "DK-abc123",
            "image": "https://your-domain.com/public/userprofile/rahul.jpg"
        },
        "bookingdetails": {
            "booking_id": "doc-a1b2c3d4-e5f6-7890-abcd-ef1234567890",
            "customer_id": "DK-abc123",
            "category": 4,
            "distance": 12.5,
            "status": 2,
            "pincode": "600001",
            "title": "Office Drop-off",
            "content": "Please arrive on time"
        },
        "starting_location": {
            "address1": "123 Main Street",
            "address2": "Near Metro Station",
            "address3": null,
            "city": "Chennai",
            "state": "Tamil Nadu",
            "country": "India",
            "postal_code": "600001",
            "landmark": "Metro Pillar 45",
            "lat": "13.0827",
            "long": "80.2707"
        },
        "ending_location": {
            "address1": "456 Central Plaza",
            "address2": null,
            "address3": null,
            "city": "Chennai",
            "state": "Tamil Nadu",
            "country": "India",
            "postal_code": "600002",
            "landmark": "Opposite Park",
            "lat": null,
            "long": null
        },
        "price": {
            "total": 285.6,
            "base_price": 225.0,
            "tax": 40.6,
            "tax_split_1": 20.3,
            "tax_split_2": 20.3,
            "coupon_id": null,
            "coupon_amount": null
        },
        "distance": 13
    }
}
```

### ❌ Validation Error — `422 Unprocessable Entity`

```json
{
    "success": false,
    "message": "Validation Error.",
    "data": {
        "booking_id": ["The booking_id field is required."]
    }
}
```

### ❌ Application Error — `200 OK`

```json
{
    "success": false,
    "message": "Booking not found",
    "data": {}
}
```

---

## API 3: Check Payment Status

| Property         | Value                    |
| ---------------- | ------------------------ |
| **Endpoint**     | `GET /api/paymentStatus` |
| **Method**       | GET                      |
| **Content-Type** | `application/json`       |

### Headers

| Header       | Value              | Required |
| ------------ | ------------------ | -------- |
| Content-Type | `application/json` | Yes      |
| X-API-Key    | `your-api-key`     | Yes      |
| Accept       | `application/json` | No       |

> -   For external usage, always send `X-API-Key`.

### Query Parameters

| Param        | Type     | Required | Description               |
| ------------ | -------- | -------- | ------------------------- |
| `booking_id` | `string` | Yes      | Unique booking identifier |

### Request Example

```
GET https://your-domain.com/api/paymentStatus?booking_id=doc-a1b2c3d4-e5f6-7890-abcd-ef1234567890
```

### Validation Rules

| Field        | Rules                | Condition |
| ------------ | -------------------- | --------- |
| `booking_id` | `required`, `string` | Always    |

### Field Explanation

| Field        | Type     | Description                                        |
| ------------ | -------- | -------------------------------------------------- |
| `booking_id` | `string` | Unique booking identifier (format: `doc-xxxxxxxx`) |

### Notes

-   Checks if a payment record exists for the given `booking_id` and whether its status equals `1` (paid).
-   Response returns a **boolean** `status` field indicating payment state.
-   If no `booking_payment` record exists, defaults to `status: false` (unpaid).
-   **HTTP status is always `200`** — check the `status` field inside the JSON for payment state.

### ✅ Success Response — Paid — `200 OK`

```json
{
    "status": true,
    "message": "paid"
}
```

### ✅ Success Response — Unpaid — `200 OK`

```json
{
    "status": false,
    "message": "unpaid"
}
```

### ❌ Validation Error — `422 Unprocessable Entity`

```json
{
    "success": false,
    "message": "Validation Error.",
    "data": {
        "booking_id": ["The booking_id field is required."]
    }
}
```

### ❌ Application Error — `200 OK`

```json
{
    "status": false,
    "message": "Booking Payment Not Found"
}
```

---

## Appendix A: Common HTTP Status Codes

| HTTP Code | Meaning                             |
| --------- | ----------------------------------- |
| `200`     | Request processed (check `success`) |
| `422`     | Validation failed                   |
| `401`     | Unauthorized — Invalid API Key      |
| `429`     | Rate limit exceeded                 |
| `500`     | Server error                        |

## Appendix B: Booking Status Mapping

| Numeric | Text                                               |
| ------- | -------------------------------------------------- |
| `0`     | Searching For The Driver                           |
| `1`     | Driver Has Accepted the Order And He is On The Way |
| `2`     | Booking Has Been Completed                         |
| `3`     | Booking has Been Cancelled                         |
| `4`     | Your Ride Has Started                              |

## Appendix C: Category Codes

| Code | Service Type   |
| ---- | -------------- |
| `1`  | Bike Taxi      |
| `2`  | Pickup         |
| `3`  | Buy / Delivery |
| `4`  | Auto           |
| `5`  | Cab            |
