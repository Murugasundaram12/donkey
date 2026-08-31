# API Documentation

## 1. Booking Price Calculation

- Endpoint: `POST /api/booking/calculation`
- Controller: `App\Http\Controllers\API\otherController::bookingCalculation`

### Request Body

```json
{
  "user_id": "USR123456",
  "distance": 12.5,
  "pincode": "600001",
  "category": 1
}
```

### Field Notes

- `user_id`: Required. Must exist in `users.user_id`
- `distance`: Required numeric value, minimum `0`
- `pincode`: Required 6-digit pincode, must exist in `pincode.pincode`
- `category`: Required integer. Allowed values: `1, 2, 3, 4, 5`

### Success Response

```json
{
  "success": true,
  "message": "Price calculated successfully",
  "data": {
    "total": 236.0,
    "base_price": 187.5,
    "tax_split_1": 16.25,
    "tax_split_2": 16.25,
    "tax": 32.5,
    "service_cost": 16,
    "total_without_base_price": 48.5
  }
}
```

### Response Body

- `total`: Final payable amount
- `base_price`: `price.amount * distance`
- `tax_split_1`: Half of total tax
- `tax_split_2`: Half of total tax
- `tax`: `18%` tax on `(base_price + service_cost)`
- `service_cost`: Category-based subscriber service charge
- `total_without_base_price`: `tax + service_cost`

## 2. Pincode Check

- Endpoint: `POST /api/pincheck`
- Controller: `App\Http\Controllers\API\otherController::pincheck`

### Request Body

```json
{
  "pincode": "600001",
  "category": 1
}
```

### Field Notes

- `pincode`: Required
- `category`: Required

### Success Response

```json
{
  "success": true,
  "message": "This pincode is  available.",
  "data": [
    {
      "id": 1,
      "state": "Tamil Nadu",
      "district": "Chennai",
      "city": "Chennai",
      "taluk": "Fort St George",
      "pincode": "600001",
      "usedBy": 12,
      "created_at": "2026-04-15T10:30:00.000000Z",
      "updated_at": "2026-04-15T10:30:00.000000Z"
    }
  ]
}
```

### Response Body

- `success`: Returns `true` when the pincode is serviceable for the given category
- `message`: Availability message
- `data`: Matching pincode records from the `pincode` table

---

## 3. Vendor Add Rider API (Admin createDriver Parity)

- **Endpoint**: `POST /api/vendor/riders`
- **Controller**: `App\Http\Controllers\API\Vendor\RiderController::store`
- **Authentication**: Bearer Token (`auth:sanctum`, `EnsureVendorAuthenticated`, `vendor.payment`)
- **Content-Type**: `multipart/form-data`

### Request Parameters / Form Fields

| Field Name | Type | Validation | DB Table / Column | Description |
| :--- | :--- | :--- | :--- | :--- |
| `name` | string | `required\|max:255` | `users.name`, `driver.name` | Driver full name |
| `mobile` | string | `required\|max:15\|unique:users,phone\|unique:driver,mobile` | `users.phone`, `driver.mobile` | Mobile phone number |
| `email` | string | `nullable\|email\|unique:users,email\|unique:driver,email` | `users.email`, `driver.email` | Email address |
| `password` | string | `required\|min:6` | `users.password`, `driver.password`, `driver.source` | Password (hashed for user & driver, raw stored in source) |
| `pincode` | array/string | `required` (validated against vendor pincodes) | `driver.pincode` | JSON array of pincode IDs belonging to vendor |
| `language` | array/string | `required` | `driver.language` | Comma-separated language names |
| `gender` | string | `required\|in:Male,Female,Other,male,female,other` | `users.gender` | Gender |
| `dob` | string | `nullable\|date` | `users.dob`, `users.dop` | Date of birth (YYYY-MM-DD) |
| `location` | string | `nullable\|max:255` | `driver.location` | Location / address |
| `aadharNo` | string | `required\|numeric\|unique:driver,aadharNo` | `driver.aadharNo` | Aadhar card number |
| `description` | string | `nullable` | `driver.description` | Full address description |
| `bankacno` | string | `nullable` | `driver.bankacno` | Bank account number |
| `ifsccode` | string | `nullable` | `driver.ifsccode` | Bank IFSC code |
| `licenceexpiry` | string | `nullable\|date` | `driver.licenceexpiry` | Driving licence expiry date |
| `vehicleNo` | string | `required\|max:100` | `driver.vehicleNo` | Vehicle registration number |
| `vehicleModelNo` | string | `required\|max:100` | `driver.vehicleModelNo` | Vehicle model name |
| `type` | array/string | `required` | `driver.type` | Comma-separated service category IDs (1=Bike, 2=Auto, 3=Cab) |
| `profile` / `profile_image` | file | `nullable\|mimes:jpg,png,pdf\|max:10240` | `users.image`, `users.profile_image` | Profile photo upload |
| `aadharFrontImage` | file | `nullable\|mimes:pdf,jpg,png\|max:10240` | `driver.aadharFrontImage` | Aadhar front document |
| `aadharBackImage` | file | `nullable\|mimes:pdf,jpg,png\|max:10240` | `driver.aadharBackImage` | Aadhar back document |
| `drivingLicence` | file | `nullable\|mimes:pdf,jpg,png\|max:10240` | `driver.drivingLicence` | Driving licence document |
| `rcbook` | file | `nullable\|mimes:pdf,jpg,png\|max:10240` | `driver.rcbook` | RC book document |
| `bike` | file | `nullable\|mimes:pdf,jpg,png\|max:10240` | `driver.bike` | Bike photo / document |
| `customerdocument` | file | `nullable\|mimes:pdf\|max:10240` | `driver.customerdocument` | Signed rider agreement PDF |

### Business Rules & Enforcement
- `subscriberId`: Forced server-side to `$request->user()->id`. Client-supplied subscriber IDs are ignored.
- `status`: Automatically set to `0` (Pending Approval).
- `pincode`: Validated server-side. Returns `422` if any submitted pincode ID does not belong to the authenticated vendor.

---

## 4. Vendor Consolidated Earnings & Reports API

- **Endpoint**: `GET /api/vendor/earnings-reports` (and `GET /api/vendor/reports`)
- **Controller**: `App\Http\Controllers\API\Vendor\EarningsReportController::index`
- **Authentication**: Bearer Token (`auth:sanctum`, `EnsureVendorAuthenticated`, `vendor.payment`)

### Query Parameters

| Parameter | Type | Default | Description |
| :--- | :--- | :--- | :--- |
| `period` | string | `this_month` | Options: `today`, `yesterday`, `this_week`, `this_month`, `custom`, `all` |
| `start_date` | string | null | Start date for `custom` period (YYYY-MM-DD) |
| `end_date` | string | null | End date for `custom` period (YYYY-MM-DD) |
| `status` | integer | null | Filter by booking status (0=Pending, 1=In Progress, 2=Completed, 3=Cancelled) |
| `category_id` | integer | null | Filter by service category ID |
| `rider_id` | integer | null | Filter by rider/driver ID |
| `page` | integer | 1 | Page number for paginated report list |
| `per_page` | integer | 15 | Items per page |

### Success Response

```json
{
  "status": true,
  "message": "Earnings and reports retrieved successfully",
  "data": {
    "summary": {
      "total_earnings": 15400.0,
      "today_earnings": 1200.0,
      "week_earnings": 4500.0,
      "month_earnings": 15400.0,
      "selected_range_earnings": 15400.0,
      "total_bookings": 85,
      "completed_bookings": 70,
      "in_progress_bookings": 5,
      "cancelled_bookings": 10
    },
    "filters_applied": {
      "period": "this_month",
      "start_date": "2026-08-01 00:00:00",
      "end_date": "2026-08-31 23:59:59",
      "status": null,
      "category_id": null,
      "rider_id": null
    },
    "reports": {
      "current_page": 1,
      "per_page": 15,
      "total": 85,
      "last_page": 6,
      "bookings": [
        {
          "id": 101,
          "booking_id": "BK-20260831-001",
          "status": 2,
          "status_text": "Completed",
          "category": {
            "id": 1,
            "name": "Bike Taxi"
          },
          "rider": {
            "id": 12,
            "name": "Rider John",
            "mobile": "9876500001"
          },
          "customer": {
            "name": "Customer Alice",
            "mobile": "9988776655"
          },
          "distance": "5.2 km",
          "duration": "18 mins",
          "pincode": "600001",
          "payment": {
            "base_fare": 100.0,
            "tax": 18.0,
            "service_charge": 10.0,
            "discount": 0.0,
            "total_amount": 128.0,
            "vendor_earning": 128.0,
            "payment_type": "Cash",
            "payment_status": "completed"
          },
          "created_at": "2026-08-31 14:30:00"
        }
      ]
    }
  }
}
```

---

## 5. Vendor Subscription & Payment Expiration Access Control

### Payment Status in Profile (`GET /api/vendor/me`)
The vendor profile response includes explicit payment state:
- `payment_status`: `1` (Active/Valid) or `0` (Expired/Inactive)
- `payment_expiry`: Subscription expiration date (YYYY-MM-DD)

### Automatic Expiration Middleware (`vendor.payment`)
When a vendor's subscription `expiryDate` has passed:
- `payment_status` becomes `0` automatically.
- Any attempt to access protected Vendor business APIs (Riders, Services, Pincodes, Coupons, Bookings, Reports, Settings/Update) returns **HTTP 403**:

```json
{
  "status": false,
  "message": "Your subscription/payment has expired. Please renew your payment."
}
```

### Unblocked Renewal Workflows
To allow expired vendors to recover access:
- `POST /api/vendor/login` returns token and profile data.
- `GET /api/vendor/me` allows viewing status.
- `GET /api/vendor/payments` and `GET /api/vendor/payments/{id}` allow viewing plans and initiating subscription renewal.
- `POST /api/vendor/logout` allows logging out.
