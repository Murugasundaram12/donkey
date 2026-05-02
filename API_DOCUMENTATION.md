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

### Availability Check Logic

- Price must exist for the given `pincode` and `category`
- Subscriber mapped to that pincode must be active and not blocked
- Category and pincode-based category must be enabled
- At least one driver must be available for the mapped driver type
