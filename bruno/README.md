# Donkey Vendor API Bruno Collection

Official ready-to-import Bruno API collection for **Donkey Vendor Application APIs**.

## 🚀 Quick Start Guide

### 1. Import Collection into Bruno
1. Open [Bruno API Client](https://www.usebruno.com/).
2. Click **Open Collection** (or **Import Collection**).
3. Select the extracted `Donkey Vendor API` directory.

### 2. Select Environment
* In Bruno, select the **`local`** environment from the top-right environment selector.
* Default `baseUrl` environment variable: `http://127.0.0.1:8000`.

### 3. Recommended Testing Flow & Token Automation
Execute requests in the following sequence:

1. **Vendor Login** (`POST /api/vendor/login`):
   - Body: `{"login": "vendor@example.com", "password": "password123"}` (or mobile number).
   - *Automated Script*: On HTTP 200, the post-response script automatically saves `res.body.data.token` into the `{{token}}` environment variable.
2. **Vendor Profile** (`GET /api/vendor/me`):
   - Uses `Authorization: Bearer {{token}}`.
3. **Vendor Dashboard** (`GET /api/vendor/dashboard`):
   - View real-time booking totals, earnings, rider status, and active coupons.
4. **Business & Pricing**:
   - `GET /api/vendor/business`
   - `POST /api/vendor/business/update`
   - `GET /api/vendor/work-description`
   - `POST /api/vendor/work-description/update`
5. **Bookings Management**:
   - `GET /api/vendor/bookings/today`
   - `GET /api/vendor/bookings/incomplete`
   - `GET /api/vendor/bookings`
   - `GET /api/vendor/bookings/:id`
   - `POST /api/vendor/bookings/:id/accept`
   - `POST /api/vendor/bookings/:id/reject`
   - `POST /api/vendor/bookings/:id/status`
   - `POST /api/vendor/bookings/:id/assign-rider`
6. **Rider Management**:
   - `GET /api/vendor/riders`
   - `POST /api/vendor/riders`
   - `GET /api/vendor/riders/:id`
   - `PUT /api/vendor/riders/:id`
   - `DELETE /api/vendor/riders/:id`
7. **Coupons & Payments**:
   - `GET /api/vendor/coupons/active`
   - `GET /api/vendor/payments`
   - `GET /api/vendor/payments/:id`
8. **Bank Details & Documents**:
   - `GET /api/vendor/bank-details`
   - `POST /api/vendor/bank-details/update` (Multipart)
   - `GET /api/vendor/documents`
   - `POST /api/vendor/documents` (Multipart: upload `document_type` and `document_file`)
   - `DELETE /api/vendor/documents/:type`
9. **Notifications & Reports**:
   - `GET /api/vendor/notifications`
   - `POST /api/vendor/notifications/:id/read`
   - `POST /api/vendor/notifications/read-all`
   - `GET /api/vendor/reports`
10. **Settings & Info**:
    - `GET /api/vendor/settings`
    - `POST /api/vendor/settings/update`
    - `GET /api/vendor/info/support`
    - `GET /api/vendor/info/terms`
    - `GET /api/vendor/info/privacy`
    - `GET /api/vendor/info/about`
11. **Vendor Logout** (`POST /api/vendor/logout`):
    - Revokes current Sanctum Bearer token.

---

## 📁 APIs Requiring File Uploads (Multipart Form-Data)

| Endpoint | Multipart Field Names | Description |
| :--- | :--- | :--- |
| **`POST /api/vendor/business/update`** | `image` (file) | Business logo |
| **`POST /api/vendor/bank-details/update`** | `bankstatement` (file) | Bank statement (PDF/Image) |
| **`POST /api/vendor/documents`** | `document_file` (file), `document_type` (text) | Verification document (`aadhar_front`, `aadhar_back`, `pan_card`, `bank_statement`, `customer_document`, `qr`, `video`, `profile`) |

---

## 🔒 Security & Privacy Statement
* Zero production secrets, database credentials, API keys, passwords, or user identity documents are stored in these collection files.
