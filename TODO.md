# Companies Module Enhancement - TODO

## Phase 1: Database Enhancement

-   [x] Create migration to add new fields to companies table (gst_number, website, logo, status, city, state, country, pincode, contact_person, contact_person_phone, deleted_at)

## Phase 2: Model Upgrade

-   [x] Update `app/Models/Company.php` with new fillable fields, casts, scopes, logo accessor

## Phase 3: Admin Controller Upgrade

-   [x] Update `app/Http/Controllers/Admin/CompanyController.php` with search/filter, logo upload, toggleStatus

## Phase 4: API Controller Upgrade

-   [x] Fix field naming inconsistency in `app/Http/Controllers/API/CompanyController.php`
-   [x] Add logo upload support and expanded validation

## Phase 5: View Upgrades

-   [x] Fix `resources/views/admin/companies/index.blade.php` (nested form bug, search, status badges, logo)
-   [x] Enhance `resources/views/admin/companies/create.blade.php` (new fields, logo upload)
-   [x] Enhance `resources/views/admin/companies/edit.blade.php` (new fields, logo preview)
-   [x] Enhance `resources/views/admin/companies/show.blade.php` (all fields, statistics)

## Phase 6: Routes Cleanup

-   [x] Fix duplicate API route in `routes/api.php`
-   [x] Add toggle-status route in `routes/web.php`

## Phase 7: Testing & Verification

-   [x] Run `php artisan migrate`
-   [x] Create `public/company-logos/` directory (storage link created)
-   [x] PHP syntax validation passed for all modified files
-   [ ] Test admin CRUD + logo upload (manual testing required)
-   [ ] Test API endpoints (manual testing required)
-   [ ] Verify delete functionality works (manual testing required)
