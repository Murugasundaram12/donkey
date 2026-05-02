# Booking APIs Reference

Base URL: `/api`

## Common Response Formats

### Success (BaseController::sendResponse)
```json
{
  "success": true,
  "message": "Booking Details",
  "data": {}
}
```

### Error (BaseController::sendError)
```json
{
  "success": false,
  "message": "Validation Error.",
  "data": {
    "field": ["The field is required."]
  }
}
```

Note: Some endpoints return `status` instead of `success` (legacy JSON response).

---

## 1) GET `/api/automaticBookingCancel`

Headers:
| Header | Value | Required |
| --- | --- | --- |
| Accept | `application/json` | Yes |
| X-API-Key | `your-api-key` | Optional |

Request body: none

Success response example:
```json
{
  "status": true,
  "message": "3 booking(s) cancelled"
}
```

Error response example:
```json
{
  "status": false,
  "message": "No bookings to cancel"
}
```

---

## 2) GET `/api/automaticBookingComplete`

Headers:
| Header | Value | Required |
| --- | --- | --- |
| Accept | `application/json` | Yes |
| X-API-Key | `your-api-key` | Optional |

Request body: none

Success response example:
```json
{
  "status": true,
  "message": "2bookings completed"
}
```

Error response example:
```json
{
  "status": false,
  "message": "No bookings to complete"
}
```

---

## 3) GET `/api/bookingStatus`

Headers:
| Header | Value | Required |
| --- | --- | --- |
| Accept | `application/json` | Yes |
| X-API-Key | `your-api-key` | Optional |

Request body:
```json
{
  "booking_id": "doc-XXXXXXXXXX"
}
```

Success response example:
```json
{
  "status": true,
  "message": "Paid Successfully"
}
```

Error response examples:
```json
{
  "status": false,
  "message": "Booking is Not Completed"
}
```
```json
{
  "status": false,
  "message": "Unpaid"
}
```

---

## 4) POST `/api/getBookingViaLocation`

Headers:
| Header | Value | Required |
| --- | --- | --- |
| Content-Type | `application/json` | Yes |
| Accept | `application/json` | Yes |
| X-API-Key | `your-api-key` | Optional |

Request body:
```json
{
  "driver_user_id": "DRV123",
  "radius": 1000
}
```

Success response example:
```json
{
  "success": true,
  "message": "Booking Details",
  "data": []
}
```

Error response example:
```json
{
  "success": false,
  "message": "Driver not found",
  "data": []
}
```

---

## 5) POST `/api/booking/calculation`

Headers:
| Header | Value | Required |
| --- | --- | --- |
| Content-Type | `application/json` | Yes |
| Accept | `application/json` | Yes |
| X-API-Key | `your-api-key` | Optional |

Request body:
```json
{
  "source": 0,
  "user_id": "USR001",
  "distance": 12.5,
  "pincode": "600001",
  "category": 1
}
```

For external source:
```json
{
  "source": 1,
  "external_phone": "9876543210",
  "distance": 12.5,
  "pincode": "600001",
  "category": 1
}
```
Note: `company_id` request body-ல் pass பண்ண வேண்டாம்; valid `X-API-Key` header மூலம் company auto-resolve ஆகும்.

Success response example:
```json
{
  "success": true,
  "message": "Price calculated successfully",
  "data": {
    "total": 180.5,
    "base_price": 150,
    "service_cost": 10,
    "tax": 20.5,
    "tax_split": {
      "cgst": 10.25,
      "sgst": 10.25
    }
  }
}
```

Error response examples:
```json
{
  "success": false,
  "message": "Validation Error.",
  "data": {}
}
```
```json
{
  "success": false,
  "message": "Invalid pincode"
}
```

---

## 6) POST `/api/booking/{action}`

Headers:
| Header | Value | Required |
| --- | --- | --- |
| Content-Type | `application/json` | Yes |
| Accept | `application/json` | Yes |
| X-API-Key | `your-api-key` | Optional |

Supported actions:
- `list`
- `cancel`
- `details`
- `calculation`
- `create`
- `delete`

Request body (varies by action):
```json
{
  "user_id": "USR001",
  "booking_id": "doc-XXXXXXXXXX"
}
```

Success response example:
```json
{
  "success": true,
  "message": "success.",
  "data": []
}
```

Error response example:
```json
{
  "success": false,
  "message": "Validation Error.",
  "data": {
    "user_id": ["The user id field is required."]
  }
}
```

---

## 7) POST `/api/bookingtaxi`

Headers:
| Header | Value | Required |
| --- | --- | --- |
| Content-Type | `application/json` | Yes |
| Accept | `application/json` | Yes |
| X-API-Key | `your-api-key` | Optional |

Request body:
```json
{
  "source": 0,
  "user_id": "USR001",
  "distance": 12.5,
  "duration": 25,
  "pincode": "600001",
  "category": 1,
  "description": "Pickup and drop",
  "from_location": {
    "address1": "A",
    "address2": "B",
    "address3": "C",
    "city": "Chennai",
    "state": "TN",
    "country": "India",
    "postal_code": "600001",
    "lat": "13.08",
    "long": "80.27",
    "landmark": "Near signal"
  },
  "to_location": [
    {
      "address1": "X",
      "address2": "Y",
      "address3": "Z",
      "city": "Chennai",
      "state": "TN",
      "country": "India",
      "postal_code": "600002",
      "lat": "13.09",
      "long": "80.28",
      "landmark": "Bus stop"
    }
  ],
  "payment_method": "cash",
  "coupon_id": null,
  "coupon_amount": null
}
```

External booking body (source=1):
```json
{
  "source": 1,
  "external_name": "Rahul",
  "external_phone": "9876543210"
}
```
Note: `company_id` pass பண்ண வேண்டாம். `X-API-Key` இருந்தால் middleware company-யை set பண்ணும்.

Success response example:
```json
{
  "success": true,
  "message": "Booking Has Been Successfull",
  "data": {
    "booking_id": "doc-XXXXXXXXXX",
    "bookingallowingstatus": 0
  }
}
```

Error response examples:
```json
{
  "success": false,
  "message": "Sorry You Have Already Booked",
  "data": {
    "bookingallowingstatus": 1
  }
}
```
```json
{
  "success": false,
  "message": "Validation Error.",
  "data": {}
}
```

---

## 8) POST `/api/bookingtaxi1`

Headers:
| Header | Value | Required |
| --- | --- | --- |
| Content-Type | `application/json` | Yes |
| Accept | `application/json` | Yes |
| X-API-Key | `your-api-key` | Optional |

Request body:
```json
{
  "user_id": "USR001",
  "distance": 12.5,
  "pincode": "600001",
  "category": 1,
  "description": "Ride",
  "from_location": {},
  "to_location": [{}]
}
```

Success response example:
```json
{
  "success": true,
  "message": "Booking Has Been Successfull",
  "data": {
    "booking_id": "doc-XXXXXXXXXX",
    "bookingallowingstatus": 0
  }
}
```

Error response example:
```json
{
  "success": false,
  "message": "Sorry You Have Already Booked",
  "data": {
    "bookingallowingstatus": 1
  }
}
```

---

## 9) POST `/api/getbookingdetailsofid`

Headers:
| Header | Value | Required |
| --- | --- | --- |
| Content-Type | `application/json` | Yes |
| Accept | `application/json` | Yes |
| X-API-Key | `your-api-key` | Optional |

Request body:
```json
{
  "booking_id": "doc-XXXXXXXXXX"
}
```

Success response example:
```json
{
  "success": true,
  "message": "Booking Details",
  "data": {
    "user": {},
    "bookingdetails": {},
    "starting_location": {},
    "ending_location": {},
    "price": {},
    "distance": 12
  }
}
```

Error response examples:
```json
{
  "success": false,
  "message": "Booking not found",
  "data": []
}
```
```json
{
  "success": false,
  "message": "Location mapping not found",
  "data": []
}
```

---

## 10) POST `/api/getbookingdetails`

Headers:
| Header | Value | Required |
| --- | --- | --- |
| Content-Type | `application/json` | Yes |
| Accept | `application/json` | Yes |
| X-API-Key | `your-api-key` | Optional |

Request body:
```json
{
  "driver_user_id": "DRV123",
  "radius": 1000
}
```

Success response example:
```json
{
  "success": true,
  "message": "Booking Details",
  "data": []
}
```

Error response example:
```json
{
  "success": false,
  "message": "Driver not found",
  "data": []
}
```

---

## 11) POST `/api/bookingaccept`

Headers:
| Header | Value | Required |
| --- | --- | --- |
| Content-Type | `application/json` | Yes |
| Accept | `application/json` | Yes |
| X-API-Key | `your-api-key` | Optional |

Request body:
```json
{
  "source": 0,
  "user_id": "USR001",
  "booking_id": "doc-XXXXXXXXXX",
  "driver_id": "DRV123"
}
```

External source request:
```json
{
  "source": 1,
  "external_phone": "9876543210",
  "booking_id": "doc-XXXXXXXXXX",
  "driver_id": "DRV123"
}
```

Success response example:
```json
{
  "status": true,
  "message": "Booking Accepted Successfully"
}
```

Error response example:
```json
{
  "status": false,
  "message": "Cannot accept booking"
}
```

---

## 12) POST `/api/bookingignore`

Headers:
| Header | Value | Required |
| --- | --- | --- |
| Content-Type | `application/json` | Yes |
| Accept | `application/json` | Yes |
| X-API-Key | `your-api-key` | Optional |

Request body:
```json
{
  "booking_id": "doc-XXXXXXXXXX",
  "driver_id": "DRV123"
}
```

Success response example:
```json
{
  "success": true,
  "message": "Booking Ignored",
  "data": "Booking Ignored"
}
```

Error response example:
```json
{
  "success": false,
  "message": "Validation Error.",
  "data": {}
}
```

---

## 13) POST `/api/bookingcancel`

Headers:
| Header | Value | Required |
| --- | --- | --- |
| Content-Type | `application/json` | Yes |
| Accept | `application/json` | Yes |
| X-API-Key | `your-api-key` | Optional |

Request body:
```json
{
  "booking_id": "doc-XXXXXXXXXX",
  "driver_id": "DRV123",
  "reason": "Not reachable"
}
```

Success response example:
```json
{
  "success": true,
  "message": "Booking Canceled By Driver",
  "data": "Booking Canceled By Driver"
}
```

Error response example:
```json
{
  "success": false,
  "message": "Validation Error.",
  "data": {}
}
```

---

## 14) POST `/api/userbookingcancel`

Headers:
| Header | Value | Required |
| --- | --- | --- |
| Content-Type | `application/json` | Yes |
| Accept | `application/json` | Yes |
| X-API-Key | `your-api-key` | Optional |

Request body:
```json
{
  "booking_id": "doc-XXXXXXXXXX",
  "reason": "Plan changed"
}
```

Success response example:
```json
{
  "success": true,
  "message": "Booking Canceled  By User",
  "data": "Booking Canceled  By User"
}
```

Error response example:
```json
{
  "success": false,
  "message": "Validation Error.",
  "data": {}
}
```

---

## 15) POST `/api/userbookingcancelwithoutreason`

Headers:
| Header | Value | Required |
| --- | --- | --- |
| Content-Type | `application/json` | Yes |
| Accept | `application/json` | Yes |
| X-API-Key | `your-api-key` | Optional |

Request body:
```json
{
  "booking_id": "doc-XXXXXXXXXX"
}
```

Success response example:
```json
{
  "success": true,
  "message": "Booking Canceled  By User",
  "data": "Booking Canceled  By User"
}
```

Error response example:
```json
{
  "success": false,
  "message": "Validation Error.",
  "data": {}
}
```

---

## 16) POST `/api/bookingdetailsoftheuser`

Headers:
| Header | Value | Required |
| --- | --- | --- |
| Content-Type | `application/json` | Yes |
| Accept | `application/json` | Yes |
| X-API-Key | `your-api-key` | Optional |

Request body:
```json
{
  "source": 0,
  "user_id": "USR001"
}
```

or (external):
```json
{
  "source": 1,
  "external_phone": "9876543210"
}
```

Success response example:
```json
{
  "success": true,
  "message": "Booking Details",
  "data": {}
}
```

Error response example:
```json
{
  "success": false,
  "message": "Validation Error.",
  "data": {}
}
```

---

## 17) POST `/api/driverbookingcomplete`

Headers:
| Header | Value | Required |
| --- | --- | --- |
| Content-Type | `application/json` | Yes |
| Accept | `application/json` | Yes |
| X-API-Key | `your-api-key` | Optional |

Request body:
```json
{
  "booking_id": "doc-XXXXXXXXXX"
}
```

Success response example:
```json
{
  "success": true,
  "message": "Booking Completed",
  "data": "doc-XXXXXXXXXX"
}
```

Error response example:
```json
{
  "success": false,
  "message": "Validation Error.",
  "data": {}
}
```

---

## 18) POST `/api/getstatusofbooking`

Headers:
| Header | Value | Required |
| --- | --- | --- |
| Content-Type | `application/json` | Yes |
| Accept | `application/json` | Yes |
| X-API-Key | `your-api-key` | Optional |

Request body:
```json
{
  "booking_id": "doc-XXXXXXXXXX"
}
```

Success response example:
```json
{
  "success": true,
  "message": "Booking Status",
  "data": {
    "booking_status": "Booking Has Been Completed",
    "booking_id": "doc-XXXXXXXXXX"
  }
}
```

Error response example:
```json
{
  "success": false,
  "message": "Validation Error.",
  "data": {}
}
```

---

## 19) POST `/api/bookinghistory`

Headers:
| Header | Value | Required |
| --- | --- | --- |
| Content-Type | `application/json` | Yes |
| Accept | `application/json` | Yes |
| X-API-Key | `your-api-key` | Optional |

Request body:
```json
{
  "source": 0,
  "user_id": "USR001"
}
```

or (external):
```json
{
  "source": 1,
  "external_phone": "9876543210"
}
```

Success response example:
```json
{
  "success": true,
  "message": "Booking History",
  "data": []
}
```

Error response example:
```json
{
  "success": false,
  "message": "User not found",
  "data": []
}
```

---

## 20) POST `/api/userBookingHistory`

Headers:
| Header | Value | Required |
| --- | --- | --- |
| Content-Type | `application/json` | Yes |
| Accept | `application/json` | Yes |
| X-API-Key | `your-api-key` | Optional |

Request body:
```json
{
  "user_id": "USR001",
  "date": "2026-04-27"
}
```

Success response example:
```json
{
  "success": true,
  "message": "Booking History of user",
  "data": []
}
```

Error response example:
```json
{
  "success": false,
  "message": "Validation Error.",
  "data": {}
}
```

---

## External Booking APIs (API Key Required)

Base URL: `/api/external`  
Middleware: `company.api`

Endpoints:
- `POST /api/external/bookingtaxi`
- `POST /api/external/bookinghistory`
- `POST /api/external/bookingdetailsoftheuser`
- `POST /api/external/getbookingdetailsofid`

Request/response format is same as corresponding non-external endpoints, but you must pass valid API key in header (based on middleware setup).

---

## External App Detailed Reference

### 1) POST `/api/external/bookingtaxi`

Headers:
| Header | Value | Required |
| --- | --- | --- |
| Content-Type | `application/json` | Yes |
| Accept | `application/json` | Yes |
| X-API-Key | `your-api-key` | Yes |

Request body:
```json
{
  "source": 1,
  "external_name": "Rahul",
  "external_phone": "9876543210",
  "distance": 12.5,
  "duration": 25,
  "pincode": "600001",
  "category": 1,
  "description": "Pickup and drop",
  "from_location": {
    "address1": "A",
    "address2": "B",
    "address3": "C",
    "city": "Chennai",
    "state": "TN",
    "country": "India",
    "postal_code": "600001",
    "lat": "13.08",
    "long": "80.27",
    "landmark": "Near signal"
  },
  "to_location": [
    {
      "address1": "X",
      "address2": "Y",
      "address3": "Z",
      "city": "Chennai",
      "state": "TN",
      "country": "India",
      "postal_code": "600002",
      "lat": "13.09",
      "long": "80.28",
      "landmark": "Bus stop"
    }
  ],
  "payment_method": "cash",
  "coupon_id": null,
  "coupon_amount": null
}
```
Note: `company_id` field அனுப்ப வேண்டாம். Header-ல் `X-API-Key` மட்டும் போதும்.

Success response example:
```json
{
  "success": true,
  "message": "Booking Has Been Successfull",
  "data": {
    "booking_id": "doc-XXXXXXXXXX",
    "bookingallowingstatus": 0
  }
}
```

Error response examples:
```json
{
  "success": false,
  "message": "Validation Error.",
  "data": {
    "external_phone": ["The external phone field is required."]
  }
}
```
```json
{
  "success": false,
  "message": "Sorry You Have Already Booked",
  "data": {
    "bookingallowingstatus": 1
  }
}
```

### 2) POST `/api/external/bookinghistory`

Headers:
| Header | Value | Required |
| --- | --- | --- |
| Content-Type | `application/json` | Yes |
| Accept | `application/json` | Yes |
| X-API-Key | `your-api-key` | Yes |

Request body:
```json
{
  "source": 1,
  "external_phone": "9876543210"
}
```

Success response example:
```json
{
  "success": true,
  "message": "Booking History",
  "data": []
}
```

Error response examples:
```json
{
  "success": false,
  "message": "Validation Error.",
  "data": {
    "source": ["The source field is required."]
  }
}
```
```json
{
  "success": false,
  "message": "User not found",
  "data": {}
}
```

### 3) POST `/api/external/bookingdetailsoftheuser`

Headers:
| Header | Value | Required |
| --- | --- | --- |
| Content-Type | `application/json` | Yes |
| Accept | `application/json` | Yes |
| X-API-Key | `your-api-key` | Yes |

Request body:
```json
{
  "source": 1,
  "external_phone": "9876543210"
}
```

Success response example:
```json
{
  "success": true,
  "message": "Booking Details",
  "data": {}
}
```

Error response example:
```json
{
  "success": false,
  "message": "Validation Error.",
  "data": {
    "external_phone": ["The external phone field is required when source is 1."]
  }
}
```

### 4) POST `/api/external/getbookingdetailsofid`

Headers:
| Header | Value | Required |
| --- | --- | --- |
| Content-Type | `application/json` | Yes |
| Accept | `application/json` | Yes |
| X-API-Key | `your-api-key` | Yes |

Request body:
```json
{
  "booking_id": "doc-XXXXXXXXXX"
}
```

Success response example:
```json
{
  "success": true,
  "message": "Booking Details",
  "data": {
    "user": {},
    "bookingdetails": {},
    "starting_location": {},
    "ending_location": {},
    "price": {},
    "distance": 12
  }
}
```

Error response examples:
```json
{
  "success": false,
  "message": "Validation Error.",
  "data": {
    "booking_id": ["The booking id field is required."]
  }
}
```
```json
{
  "success": false,
  "message": "Booking not found",
  "data": {}
}
```
