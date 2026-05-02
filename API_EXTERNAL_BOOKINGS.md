# Donkey Booking System - External Bookings API Documentation

## Overview

API supports two booking types:

-   **source=0**: Normal app user (requires `user_id`)
-   **source=1**: External booking (requires `external_phone` + `company_id`, `user_id` nullable)

**Base URL:** `http://localhost/donkey/api` (dev) / `https://demo.donkeydeliveries.com/doNkey/api` (prod)

**Response Format:**

```json
{
    "success": true,
    "message": "Success message",
    "data": {}
}
```

---

## 1. Payment Status Update

**Endpoint:** `POST /payment_status`

**Request Body:**

```json
// source=0
{
  "booking_id": "doc-xxx",
  "order_id": "order_123",
  "status": 1
}

// source=1 (same format)
{
  "booking_id": "doc-xxx",
  "order_id": "order_123",
  "status": 1
}
```

**Validation Rules:**

```
booking_id: required|string|exists:booking,booking_id
order_id: required|string
status: required|integer
```

**Success Response (200):**

```json
{
    "success": true,
    "message": "Booking Status Updated Successfully",
    "data": {}
}
```

**Error Responses:**

```json
// 404
{
  "success": false,
  "message": "Booking Not Found",
  "data": {}
}

// 422 Validation
{
  "message": "The given data was invalid.",
  "errors": {
    "booking_id": ["The booking id field is required."]
  }
}
```

**Explanation:** Updates payment status for completed booking. No source validation needed as it's booking-owned.

**Notes:**

-   Works for both source types
-   No notifications triggered
-   `status=1` (paid), `0` (unpaid)

---

## 2. Submit Rating

**Endpoint:** `POST /rating`

**Request Body:**

```json
{
    "booking_id": "doc-4369007316",
    "driver_id": 232,
    "rating": 3,
    "remarks": "Great service!"
}
```

**Validation Rules:**

```
booking_id: required|string
driver_id: required|integer
rating: required|integer|min:1|max:5
remarks: nullable|string
```

**Success Response (200):**

```json
{
    "success": true,
    "message": "Rated Successfully",
    "data": {}
}
```

**Error Responses:**

```json
// Booking/driver mismatch
{
  "success": false,
  "message": "Booking Not Found or Driver Not Assigned",
  "data": {}
}

// Already rated
{
  "success": false,
  "message": "Already Rated",
  "data": {}
}

// Wrong status
{
  "success": false,
  "message": "unable to rate now, Status MisMatch (must be completed/cancelled)",
  "data": {}
}
```

**Explanation:** Rate completed/cancelled booking (status 2/3 only).

**Notes:**

-   No source/user_id needed (verifies via booking record)
-   Stores `customer_id`/`external_phone` in ratings table
-   Prevents duplicate ratings

---

## 3. Accept Booking

**Endpoint:** `POST /bookingaccept`

**Request Body:**

```json
// source=0
{
  "source": 0,
  "user_id": "DK-xxx",
  "booking_id": "doc-xxx",
  "driver_id": 232
}

// source=1
{
  "source": 1,
  "external_phone": "9876543210",
  "booking_id": "doc-xxx",
  "driver_id": 232
}
```

**Validation Rules:**

```
source: required|in:0,1
user_id: required_if:source,0|nullable
external_phone: required_if:source,1|nullable
booking_id: required
driver_id: required
```

**Success Response (200):**

```json
{
    "success": true,
    "message": "Booking Accepted Successfully",
    "data": {}
}
```

**Error Responses:**

```json
// Already accepted
{
  "success": false,
  "message": "Cannot accept booking",
  "data": {}
}

// Unauthorized
{
  "success": false,
  "message": "Unauthorized",
  "data": {}
}
```

**Explanation:** Driver accepts pending booking (status=0).

**Notes:**

-   ✅ **Triggers notifications** to customer via FCM
-   Resets driver `is_radius=0`
-   Updates booking `accepted` + `status=1`

---

## 4. Live Tracking - User Location

**Endpoint:** `POST /livetrackinguser`

**Request Body:**

```json
{
    "booking_id": "doc-xxx",
    "latitude": 12.9716,
    "longitude": 77.5946,
    "speed": 25.5
}
```

**Validation Rules:**

```
booking_id: required
latitude: required
longitude: required
speed: required
```

**Success Response (200):**

```json
{
    "success": true,
    "message": "Track user location and gives to driver",
    "data": {
        "driver_latitude": 12.97,
        "driver_longitude": 77.59,
        "user_latitude": 12.9716,
        "user_longitude": 77.5946
    }
}
```

**Error Responses:**

```json
{
    "success": false,
    "message": "Booking Not Found",
    "data": {}
}
```

**Explanation:** Updates user location during trip; returns driver location.

**Notes:**

-   No source validation (live tracking only)
-   Updates `driver_lat/long/speed` in booking table
-   No notifications

---

## 5. User Location

**Endpoint:** `POST /locationuser`

**Request Body:**

```json
{
    "booking_id": "doc-xxx"
}
```

**Validation Rules:**

```
booking_id: required
```

**Success Response (200):**

```json
{
    "success": true,
    "message": "User location",
    "data": {
        "user_lat": 12.9716,
        "user_long": 77.5946,
        "speed": 25.5
    }
}
```

**Error Responses:**

```json
{
    "success": false,
    "message": "Booking Not Found",
    "data": {}
}
```

**Explanation:** Get current user location from active booking.

**Notes:**

-   No source validation
-   Returns from booking table
-   No updates/notifications

---

## Usage Guidelines

**Frontend Integration:**

```
- Always send source in create/booking APIs
- For ratings/payments: booking_id sufficient
- Live tracking: POST location updates continuously
- Error handling: Check `success` flag first
```

**Backend Security:**

-   Ownership verified via booking records
-   No user_id needed for most external flows
-   Rate limiting recommended on live tracking

**Ready for production deployment!** 📱
