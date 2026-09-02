<?php

use App\Http\Controllers\API\InfoController;
use App\Http\Controllers\API\MessageControler;
use App\Http\Controllers\CacheController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\otherController;
use App\Http\Controllers\API\User\DriverLocationController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/clear', [CacheController::class, 'clear']);
Route::get('qr', [otherController::class, 'getQr'])->name('qr');
Route::get('getDriversLocation', [DriverLocationController::class, 'index'])->name('getDriversLocation');
Route::get('driver-arrived', [otherController::class, 'driverarrived'])->name('driverarrived')->middleware('detect.company');
Route::resource('info', InfoController::class)->only('index');
Route::get('pincodeDetails', [otherController::class, 'pincodeDetails'])->name('pincodeDetails');
Route::get('readBy', [MessageControler::class, 'readBy'])->name('api.readBy');
Route::get('redDot', [MessageControler::class, 'redDot'])->name('redDot');
Route::post('userToken', [otherController::class, 'userToken'])->name('userToken');
Route::post('driverToken', [otherController::class, 'driverToken'])->name('driverToken');
Route::get('automaticBookingCancel', [otherController::class, 'automaticBookingCancel'])->name('api.automaticBookingCancel');
Route::get('automaticBookingComplete', [otherController::class, 'automaticBookingComplete'])->name('api.automaticBookingComplete');
Route::get('maintainance', [otherController::class, 'maintainance'])->name('maintainance');
Route::get('coupons', [otherController::class, 'coupons'])->name('api.coupons');
Route::get('couponList', [otherController::class, 'couponList'])->name('couponList');
Route::get('appVerision', [otherController::class, 'appVerision'])->name('appVerision');
Route::post('reportDriver', [otherController::class, 'reportDriver'])->name('reportDriver');
Route::get('paymentStatus', [otherController::class, 'paymentStatus'])->name('paymentStatus');
Route::get('bookingStatus', [otherController::class, 'bookingStatus'])->name('bookingStatus');
Route::get('driverDocument', [otherController::class, 'driverDocument'])->name('driverDocument');
Route::post('activeRadius', [otherController::class, 'activeRadius'])->name('activeRadius');
Route::get('checkIsRadius', [otherController::class, 'checkIsRadius'])->name('checkIsRadius');
Route::post('getBookingViaLocation', [otherController::class, 'getBookingViaLocation'])->name('getBookingViaLocation');
Route::post('googleSignIn', [otherController::class, 'googleSignIn'])->name('googleSignIn');
Route::post('googleSignUp', [otherController::class, 'googleSignUp'])->name('googleSignUp');
Route::post('drivercurrentlocation', [otherController::class, 'drivercurrentlocation'])->name('drivercurrentlocation');
Route::post("payment_status", [otherController::class, 'payment_status'])->name('payment_status');
Route::post('driverStatus', [otherController::class, 'driverStatus'])->name('driverStatus');
Route::get('currentStatus', [otherController::class, 'currentStatus'])->name('currentStatus');
Route::get('availablePincode', [otherController::class, 'availablePincode'])->name('availablePincode');
Route::delete('deleteAddress/{id}', [otherController::class, 'deleteAddress'])->name('deleteAddress');
Route::get('favouriteAddressList', [otherController::class, 'favouriteAddressList'])->name('favouriteAddressList');
Route::post('addAddress', [otherController::class, 'addAddress'])->name('addAddress');
Route::get('userProfile', [otherController::class, 'userProfile'])->name('api.userProfile');
Route::put('updateProfile/{id}', [otherController::class, 'updateProfile'])->name('updateProfile');
Route::put('updateMobileNumber/{id}', [otherController::class, 'updateMobileNumber'])->name('updateMobileNumber');
Route::post('forgotPassword', [otherController::class, 'forgot'])->name('api.forgotPassword');
Route::post('/register', [App\Http\Controllers\API\RegisterController::class, 'register'])->name('api.register');
Route::post('/login', [App\Http\Controllers\API\RegisterController::class, 'login'])->name('api.login');
Route::post('sendMessage', [MessageControler::class, 'createMessage'])->name('sendMessage');
Route::get('reciveMessage', [MessageControler::class, 'reciveMessage'])->name('reciveMessage');
//Route::middleware('auth:sanctum')->group( function () {
Route::get('/profiles', [App\Http\Controllers\API\RegisterController::class, 'profileUser'])->name('profileUser');
Route::post('/profile/verify', [App\Http\Controllers\API\RegisterController::class, 'userVerify'])->name('userVerify');
Route::post("/booking/calculation", [App\Http\Controllers\API\otherController::class, "bookingCalculation"])->name('bookingCalculation');
Route::post('/booking/{action}', [App\Http\Controllers\API\Booking::class, 'index'])->name('index');

Route::apiResource('companies', \App\Http\Controllers\API\CompanyController::class);
Route::post('/profile/update', [App\Http\Controllers\API\RegisterController::class, 'profileUpdate'])->name('profileUpdate');
Route::post('/user/filter/{action}', [App\Http\Controllers\API\RegisterController::class, 'userFilter'])->name('userFilter');
Route::get('/category/list', [App\Http\Controllers\API\Category::class, 'categoryList'])->name('categoryList');
// Route::get('/logout', [App\Http\Controllers\API\RegisterController::class, 'logout'])->name('logout');
//});
Route::post('/driverlogin', [App\Http\Controllers\API\RegisterController::class, 'driverlogin'])->name('driverlogin');
Route::post('/driverlogincheck', [App\Http\Controllers\API\RegisterController::class, 'driverlogincheck'])->name('driverlogincheck');

// Route::post('notificationtodriver', [App\Http\Controllers\API\RegisterController::class, 'notificationtodriver'])->name('notificationtodriver');
// Route::post('pincheck', [App\Http\Controllers\API\RegisterController::class, 'pincheck'])->name('pincheck');
Route::post('pincheck', [App\Http\Controllers\API\otherController::class, 'pincheck'])->name('pincheck');
Route::post('notificationtodriver', [App\Http\Controllers\API\RegisterController::class, 'notificationtodriver'])->name('notificationtodriver');
Route::post('userprofile', [App\Http\Controllers\API\RegisterController::class, 'userprofile'])->name('userprofile');

// Route::post('/driverlogin', [App\Http\Controllers\API\RegisterController::class, 'driverlogin'])->name('driverlogin');
// Route::post('/driverlogincheck', [App\Http\Controllers\API\RegisterController::class, 'driverlogincheck'])->name('driverlogincheck');
// Route::post('pincheck',[App\Http\Controllers\API\RegisterController::class, 'pincheck'])->name('pincheck');

Route::post("driverprofile", [\App\Http\Controllers\API\otherController::class, "driverprofile"])->name("driverprofile");

Route::post("drivercall", [\App\Http\Controllers\API\otherController::class, "drivercall"])->name("drivercall");
Route::post("subscribercall", [\App\Http\Controllers\API\otherController::class, "subscribercall"])->name("subscribercall");
Route::post('notificationtodriverfirebase', [App\Http\Controllers\API\otherController::class, 'notificationtodriverfirebase'])->name('notificationtodriverfirebase');

Route::post("bookingtaxi", [\App\Http\Controllers\API\otherController::class, "bookingtaxi"])->middleware('detect.company');
Route::post("rating", [otherController::class, 'rating'])->name('rating');

Route::post("bookingtaxi1", [\App\Http\Controllers\API\otherController::class, "bookingtaxi1"]);

/*
|--------------------------------------------------------------------------
| EXTERNAL APIs (STRICT — API Key Required)
| URL: /api/external/*
| Middleware: company.api
| Used by: Third-party apps
|--------------------------------------------------------------------------
*/
Route::prefix('external')->middleware('company.api')->group(function () {
    Route::post('bookingtaxi', [App\Http\Controllers\API\otherController::class, 'bookingtaxi'])->name('external.bookingtaxi');
    Route::post('bookinghistory', [App\Http\Controllers\API\otherController::class, 'bookinghistory'])->name('external.bookinghistory');
    Route::post('bookingdetailsoftheuser', [App\Http\Controllers\API\otherController::class, 'bookingdetailsoftheuser'])->name('external.bookingdetailsoftheuser');
    Route::post('getbookingdetailsofid', [App\Http\Controllers\API\otherController::class, 'getbookingdetailsofid'])->name('external.getbookingdetailsofid');
    Route::post('livetrackinguser', [App\Http\Controllers\API\otherController::class, 'livetrackinguser'])->name('external.livetrackinguser');
    Route::post('locationuser', [App\Http\Controllers\API\otherController::class, 'locationuser'])->name('external.locationuser');
    Route::post('payment_status', [App\Http\Controllers\API\otherController::class, 'payment_status'])->name('external.payment_status');
    Route::get('paymentStatus', [App\Http\Controllers\API\otherController::class, 'paymentStatus'])->name('external.paymentStatus');
});


Route::post('logout', [App\Http\Controllers\API\otherController::class, "logoutapi"]);

Route::post('getbookingdetailsofid', [App\Http\Controllers\API\otherController::class, "getbookingdetailsofid"])->middleware('detect.company');
// Route::post('getbookingdetails', [App\Http\Controllers\API\otherController::class, "getbookingdetails"]);
Route::post('getbookingdetails', [App\Http\Controllers\API\otherController::class, "getbookingdetails"]);
Route::get('getlivelocation', [\App\Http\Controllers\API\otherController::class, "getlivelocation"]);

// Route::post('getbookingdetails1', [App\Http\Controllers\API\otherController::class, "getbookingdetails1"]);

Route::get('banner', [App\Http\Controllers\API\otherController::class, "banner"]);
Route::get('voucher', [App\Http\Controllers\API\otherController::class, "voucher"]);


Route::post("bookingaccept", [App\Http\Controllers\API\otherController::class, "bookingaccept"])->middleware('detect.company');
Route::post("bookingprovideraccept", [App\Http\Controllers\API\otherController::class, "bookingprovideraccept"])->middleware('detect.company');
Route::post("booking_provider_pending_list", [App\Http\Controllers\API\otherController::class, "booking_provider_pending_list"])->middleware('detect.company');
Route::post("booking_provider_assign_driver", [App\Http\Controllers\API\otherController::class, "booking_provider_assign_driver"])->middleware('detect.company');
Route::post("bookingignore", [App\Http\Controllers\API\otherController::class, "bookingignore"]);
Route::post("bookingcancel", [App\Http\Controllers\API\otherController::class, "bookingcancel"]);
Route::post("userbookingcancel", [App\Http\Controllers\API\otherController::class, "userbookingcancel"]);
Route::post("userbookingcancelwithoutreason", [App\Http\Controllers\API\otherController::class, "userbookingcancelwithoutreason"]);

Route::post("livetrackingdriver", [App\Http\Controllers\API\otherController::class, "livetrackingdriver"]);
Route::post("livetrackinguser", [App\Http\Controllers\API\otherController::class, "livetrackinguser"]);

Route::post("bookingdetailsoftheuser", [App\Http\Controllers\API\otherController::class, "bookingdetailsoftheuser"])->middleware('detect.company');

Route::post("driverbookingcomplete", [App\Http\Controllers\API\otherController::class, "driverbookingcomplete"]);

Route::post("locationuser", [App\Http\Controllers\API\otherController::class, "locationuser"]);
Route::post("locationdriver", [App\Http\Controllers\API\otherController::class, "locationdriver"]);


Route::post("checkavailabledriver", [App\Http\Controllers\API\otherController::class, "checkavailabledriver"]);
Route::post("otpverification", [App\Http\Controllers\API\otherController::class, "otpverification"]);
Route::post("otpcompleteverification", [App\Http\Controllers\API\otherController::class, "otpcompleteverification"]);

Route::post("getstatusofbooking", [App\Http\Controllers\API\otherController::class, "getstatusofbooking"]);

Route::post("bookinghistory", [App\Http\Controllers\API\otherController::class, "bookinghistory"]);
Route::post("driverhistory", [App\Http\Controllers\API\otherController::class, "driverhistory"]);
Route::post("userBookingHistory", [App\Http\Controllers\API\otherController::class, "userBookingHistory"])->name('userBookingHistory');

Route::post("sendnotificationtootherdriver", [App\Http\Controllers\API\otherController::class, "sendnotificationtootherdriver"]);


Route::post("drivertocustomercall", [App\Http\Controllers\API\otherController::class, "drivertocustomercall"]);
Route::post("checkNumber", [App\Http\Controllers\API\otherController::class, "checkNumber"])->name("checkNumber");

Route::post('generate_referral_code', [App\Http\Controllers\Auth\RegisterController::class, 'generate_referral_code']);

/*
|--------------------------------------------------------------------------
| VENDOR APP APIs
| URL: /api/vendor/*
|--------------------------------------------------------------------------
*/
Route::prefix('vendor')->group(function () {
    // Guest Auth Routes
    Route::post('login', [\App\Http\Controllers\API\Vendor\AuthController::class, 'login']);
    Route::post('otp/verify', [\App\Http\Controllers\API\Vendor\AuthController::class, 'otpVerify']);
    Route::post('otp/resend', [\App\Http\Controllers\API\Vendor\AuthController::class, 'otpResend']);
    Route::post('password/forgot', [\App\Http\Controllers\API\Vendor\AuthController::class, 'forgotPassword']);

    // Public Info Routes
    Route::get('info/support', [\App\Http\Controllers\API\Vendor\SettingsAndInfoController::class, 'support']);
    Route::get('info/terms', [\App\Http\Controllers\API\Vendor\SettingsAndInfoController::class, 'terms']);
    Route::get('info/privacy', [\App\Http\Controllers\API\Vendor\SettingsAndInfoController::class, 'privacy']);
    Route::get('info/about', [\App\Http\Controllers\API\Vendor\SettingsAndInfoController::class, 'about']);

    // Authenticated Vendor Routes
    Route::middleware(['auth:sanctum', \App\Http\Middleware\EnsureVendorAuthenticated::class])->group(function () {
        // Auth & Renewal Exempt Routes (accessible even if payment expired)
        Route::get('me', [\App\Http\Controllers\API\Vendor\AuthController::class, 'me']);
        Route::post('password/change', [\App\Http\Controllers\API\Vendor\AuthController::class, 'changePassword']);
        Route::post('logout', [\App\Http\Controllers\API\Vendor\AuthController::class, 'logout']);

        // Payments / Subscription Renewal Routes (accessible when expired to allow renewal)
        Route::get('payments', [\App\Http\Controllers\API\Vendor\PaymentController::class, 'index']);
        Route::get('payments/{id}', [\App\Http\Controllers\API\Vendor\PaymentController::class, 'show']);

        // Protected Business Routes (Blocked if vendor subscription payment is expired)
        Route::middleware('vendor.payment')->group(function () {
            // Dashboard
            Route::get('dashboard', [\App\Http\Controllers\API\Vendor\DashboardController::class, 'index']);

            // Business Profile & Pricing
            Route::get('business', [\App\Http\Controllers\API\Vendor\BusinessController::class, 'getBusiness']);
            Route::post('business/update', [\App\Http\Controllers\API\Vendor\BusinessController::class, 'updateBusiness']);
            Route::get('work-description', [\App\Http\Controllers\API\Vendor\BusinessController::class, 'getWorkDescription']);
            Route::post('work-description/update', [\App\Http\Controllers\API\Vendor\BusinessController::class, 'updateWorkDescription']);

            // Bookings
            Route::get('bookings/today', [\App\Http\Controllers\API\Vendor\BookingController::class, 'today']);
            Route::get('bookings/incomplete', [\App\Http\Controllers\API\Vendor\BookingController::class, 'incomplete']);
            Route::get('bookings', [\App\Http\Controllers\API\Vendor\BookingController::class, 'index']);
            Route::get('bookings/{id}', [\App\Http\Controllers\API\Vendor\BookingController::class, 'show']);
            Route::post('bookings/{id}/accept', [\App\Http\Controllers\API\Vendor\BookingController::class, 'accept']);
            Route::post('bookings/{id}/reject', [\App\Http\Controllers\API\Vendor\BookingController::class, 'reject']);
            Route::post('bookings/{id}/status', [\App\Http\Controllers\API\Vendor\BookingController::class, 'updateStatus']);
            Route::post('bookings/{id}/assign-rider', [\App\Http\Controllers\API\Vendor\BookingController::class, 'assignRider']);

            // Riders
            Route::get('riders/approvals', [\App\Http\Controllers\API\Vendor\RiderController::class, 'approvals']);
            Route::post('riders/{id}/approve', [\App\Http\Controllers\API\Vendor\RiderController::class, 'approve']);
            Route::post('riders/{id}/reject', [\App\Http\Controllers\API\Vendor\RiderController::class, 'reject']);
            Route::get('riders/online', [\App\Http\Controllers\API\Vendor\RiderController::class, 'online']);
            Route::apiResource('riders', \App\Http\Controllers\API\Vendor\RiderController::class);

            // Services
            Route::get('services/dashboard', [\App\Http\Controllers\API\Vendor\ServiceController::class, 'dashboard']);
            Route::get('services', [\App\Http\Controllers\API\Vendor\ServiceController::class, 'index']);
            Route::put('services/{service_id}/status', [\App\Http\Controllers\API\Vendor\ServiceController::class, 'updateStatus']);

            // Pincodes
            Route::get('pincodes/summary', [\App\Http\Controllers\API\Vendor\PincodeController::class, 'summary']);
            Route::get('pincodes', [\App\Http\Controllers\API\Vendor\PincodeController::class, 'index']);
            Route::put('pincodes/{pincode_id}/status', [\App\Http\Controllers\API\Vendor\PincodeController::class, 'updateStatus']);

            // Coupons
            Route::get('coupons/active', [\App\Http\Controllers\API\Vendor\CouponController::class, 'active']);
            Route::get('coupons/summary', [\App\Http\Controllers\API\Vendor\CouponController::class, 'summary']);

            // Bank Details
            Route::get('bank-details', [\App\Http\Controllers\API\Vendor\BankDetailsController::class, 'show']);
            Route::post('bank-details/update', [\App\Http\Controllers\API\Vendor\BankDetailsController::class, 'update']);

            // Documents
            Route::get('documents', [\App\Http\Controllers\API\Vendor\DocumentController::class, 'index']);
            Route::post('documents', [\App\Http\Controllers\API\Vendor\DocumentController::class, 'store']);
            Route::delete('documents/{type}', [\App\Http\Controllers\API\Vendor\DocumentController::class, 'destroy']);

            // Notifications
            Route::get('notifications', [\App\Http\Controllers\API\Vendor\NotificationController::class, 'index']);
            Route::get('notifications/unread-count', [\App\Http\Controllers\API\Vendor\NotificationController::class, 'unreadCount']);
            Route::post('notifications/{id}/read', [\App\Http\Controllers\API\Vendor\NotificationController::class, 'markRead']);
            Route::post('notifications/read-all', [\App\Http\Controllers\API\Vendor\NotificationController::class, 'markAllRead']);

            // Earnings & Reports
            Route::get('earnings-reports', [\App\Http\Controllers\API\Vendor\EarningsReportController::class, 'index']);
            Route::get('reports', [\App\Http\Controllers\API\Vendor\ReportController::class, 'index']);

            // Settings
            Route::get('settings', [\App\Http\Controllers\API\Vendor\SettingsAndInfoController::class, 'getSettings']);
            Route::post('settings/update', [\App\Http\Controllers\API\Vendor\SettingsAndInfoController::class, 'updateSettings']);
        });
    });
});
