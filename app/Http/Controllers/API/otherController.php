<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ForgotPassword;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Coupon;
use App\Models\Driver;
use App\Models\Pincode;
use App\Models\User;
use App\Models\voucher;
use App\Models\banner;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\Blocklist;
use App\Models\BookingRating;
use App\Models\Enduser;
use App\Models\EnduserReason;
use App\Models\Report;
use App\Models\UserAddress;
use App\Models\Unblocklist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
// use Illuminate\Contracts\Validation\Validator;
//use Validator;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Models\Subscriber;
use App\Models\site;
use App\Models\Usedcoupon;
use App\Models\Pincodebasedcategory;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Kutia\Larafirebase\Facades\Larafirebase;

class otherController extends BaseController
{
    private function isExternalBookingRequest(Request $request, ?string $externalRouteName = null): bool
    {
        if ((bool) $request->get('is_external', false)) {
            return true;
        }

        if ($externalRouteName && $request->routeIs($externalRouteName)) {
            return true;
        }

        return str_starts_with((string) $request->path(), 'api/external/');
    }

    public function bookingCalculation(Request $request)
    {
        $source = (int) $request->get('source', 0);
        $request->merge([
            'source' => $source
        ]);
        $validator = Validator::make($request->all(), [
            'source' => 'nullable|in:0,1',
            'user_id' => 'required_if:source,0|nullable|string|exists:users,user_id',
            'external_phone' => 'required_if:source,1|nullable|string',
            'company_id' => 'required_if:source,1|integer|exists:companies,id',
            'distance' => 'required|numeric|min:0',
            'pincode' => 'required|digits:6|exists:pincode,pincode',
            'category' => 'required|integer|in:1,2,3,4,5'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        // Skip user lookup for external bookings (source=1)
        if ($request->source == 0) {
            $user = User::where('user_id', $request->user_id)->first();
            if (!$user) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }
        }
        // For source=1: Just validate company_id + external_phone (no user lookup needed)
        $pincodeData = Pincode::where('pincode', $request->pincode)->first();
        if (!$pincodeData) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid pincode'
            ]);
        }

        $subscriber = Subscriber::where('id', $pincodeData->usedBy)->first();
        if (!$subscriber || $subscriber->blockedstatus != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Subscriber blocked'
            ]);
        }

        $price = DB::table('price')
            ->where('category', $request->category)
            ->where('pincode', $request->pincode)
            ->where('range_from', '<=', $request->distance)
            ->where('range_to', '>=', $request->distance)
            ->first();

        if (!$price) {
            return response()->json([
                'success' => false,
                'message' => 'Price not found'
            ]);
        }

        $serviceCostMap = [
            1 => $subscriber->biketaxi_price ?? 2,
            2 => $subscriber->pickup_price ?? 2,
            3 => $subscriber->buy_price ?? 2,
            4 => $subscriber->auto_price ?? 2,
            5 => $subscriber->cab_price ?? 2
        ];
        $service_cost = $serviceCostMap[$request->category] ?? 2;

        $base_price = round($price->amount * $request->distance, 2);
        $subtotal = $base_price + $service_cost;
        $tax = round($subtotal * 0.18, 2);
        $tax_split_1 = round($tax / 2, 2);
        $tax_split_2 = round($tax / 2, 2);
        $total = round($base_price + $tax + $service_cost, 2);

        return response()->json([
            'success' => true,
            'message' => 'Price calculated successfully',
            'data' => [
                'total' => $total,
                'base_price' => $base_price,
                'service_cost' => $service_cost,
                'tax' => $tax,
                'tax_split' => [
                    'cgst' => $tax_split_1,
                    'sgst' => $tax_split_2
                ]
            ]
        ]);
    }

    public function getQr(Request $request)
    {
        $validator = $request->validate([
            'driver_id' => ['required', Rule::exists(Driver::class, 'userid')],
        ]);

        // $booking = Booking::where('booking_id', $validator['booking_id'])->first();
        // if (isset($booking?->accepted)) {
        $driver = Driver::where('userid', $validator['driver_id'])
            ?->first();
        if (isset($driver)) {
            $subscriber = Subscriber::where('id', $driver->subscriberId)
                ->first();
            if (!isset($subscriber?->qr)) {
                return new JsonResponse([
                    'status' => false,
                    'qrImage' => $subscriber?->qr,
                    "message" => "QR code not uploaded"
                ]);
            }
            return new JsonResponse([
                'status' => true,
                'qrImage' => $subscriber?->qr
            ]);
        } else {
            return new JsonResponse([
                'status' => false,
                "message" => "driver not found"
            ]);
        }

        // } else {
        //     return new JsonResponse([
        //         'status' => false,
        //         "message" => "Booking not accepted by any driver"
        //     ], 400);
        // }
    }


    public function driverarrived(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:booking,booking_id',
            'driver_id' => 'nullable|exists:driver,userid',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $booking = Booking::where('booking_id', $request->booking_id)->first();
        if (!$booking) {
            return new JsonResponse([
                'status' => false,
                'message' => "Booking not found"
            ]);
        }

        if (empty($booking->accepted)) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Driver has not been assigned to this booking'
            ], 409);
        }

        // Newer driver apps can send driver_id for an additional ownership check.
        // Older versions send only booking_id, so keep that request compatible.
        if ($request->filled('driver_id') && (string) $booking->accepted !== (string) $request->driver_id) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Driver is not assigned to this booking'
            ], 403);
        }

        $message = "Driver Arrived Successfully";
        $driver = Driver::where('userid', $booking->accepted)->first();
        $drivername = $driver?->name ?: "Rider";
        $vehicle = $driver?->vehicleModelNo ?: "";
        $vehiclenumber = $driver?->vehicleNo ?: "";

        $user = $this->getBookingUser($booking);
        if ($user && isset($user->device_token)) {
            $content = "Your safe journey awaits. $drivername is waiting with their vehicle ($vehicle, $vehiclenumber). Please meet them within 3 minutes to avoid any additional wait time or charges. Ride pin: {$booking->otp}";
            $title = 'Rider Has Arrived!';
            $this->notification($user, $booking, $title, $content);
        }

        return new JsonResponse([
            'status' => true,
            'message' => $message
        ]);
    }

    public function pincodeDetails(Request $request)
    {
        $validated = $request->validate([
            'pincode' => 'required'
        ]);

        $pincode = Pincode::where('pincode', $validated['pincode'])
            ->first();
        if (isset($pincode)) {
            if ($pincode->usedBy != 0) {
                $subscriber = Subscriber::where('id', $pincode->usedBy)
                    ->first();
                if (isset($subscriber)) {
                    $drivers = Driver::where("subscriberId", $subscriber->id)
                        ->where("pincode", 'LIKE', "%$pincode->id%")
                        ->get();
                    $allDrivers = $drivers;
                    foreach ($drivers as $driver) {
                        $userasdriver = User::where('id', $driver->userid)
                            ->where('is_driver', 1)
                            ->first();
                        $driver->is_live = $userasdriver->is_live;
                    }
                    $driverOnlineCount = $drivers->where('is_live', 1)->count();
                    $driverOfflineCount = $drivers->where('is_live', 0)->count();
                    $othersCount = $drivers->whereNotIn('is_live', [0, 1])->count();
                    $driverOnrideCount = Booking::where("pincode", $pincode?->pincode)->where('status', 4)->get()->count();
                    //$categories = $allDrivers->pluck('type')->unique();
                    $categories = $allDrivers->pluck('type')->unique()->values();
                    return new JsonResponse([
                        'status' => true,
                        'message' => 'Details about the pincode.',
                        'subscriber' => $subscriber,
                        'driversCount' => $drivers->count(),
                        'driverOnlineCount' => $driverOnlineCount,
                        'driverOfflineCount' => $driverOfflineCount,
                        'othersCount' => $othersCount,
                        'driverOnrideCount' => $driverOnrideCount,
                        'categories' => $categories,
                        //'drivers' => $drivers
                    ]);
                } else {
                    return new JsonResponse([
                        'status' => false,
                        'message' => 'Subscriber Not Found.'
                    ]);
                }
            } else {
                return new JsonResponse([
                    'status' => false,
                    'message' => 'No data is available for this pincode.'
                ]);
            }
        } else {
            return new JsonResponse([
                'status' => false,
                'message' => 'Invalid pincode.'
            ]);
        }
    }

    public function userToken(Request $request)
    {
        $validated = $request->validate([
            'userToken' => 'required'
        ]);
        $site = site::where('id', 1)
            ->first();
        $token = "Bearer " . $validated['userToken'];
        $site->userToken = $token;
        $site->update();
        //return $site;
        return new JsonResponse([
            'status' => true,
            'message' => "Token Updated Successfully"
        ]);
    }

    public function driverToken(Request $request)
    {
        $validated = $request->validate([
            'driverToken' => 'required'
        ]);
        $site = site::where('id', 1)
            ->first();
        $token = "Bearer " . $validated['driverToken'];
        $site->driverToken = $token;
        $site->update();
        return new JsonResponse([
            'status' => true,
            'message' => "Token Updated Successfully"
        ]);
    }

    public function automaticBookingCancel()
    {
        $time = Carbon::now('Asia/Kolkata');
        $pendingBookings = DB::table('booking')
            ->where('status', 0)
            ->whereNull('accepted')
            ->get();
        if ($pendingBookings->isEmpty()) {
            return new JsonResponse([
                'status' => false,
                'message' => "No bookings to cancel"
            ]);
        }
        $cancelledCount = 0;
        foreach ($pendingBookings as $booking) {
            $createdAt = Carbon::parse($booking->created_at, 'UTC')->timezone('Asia/Kolkata');
            //return $created_at;
            //return $time;
            $diff = $createdAt->diffInMinutes($time, false);
            //return $diff;
            if ($diff >= 15) {
                DB::table('booking')
                    ->where('id', $booking->id)
                    ->update(['status' => 3]);

                $cancelledCount++;
            }
        }
        return new JsonResponse([
            'status' => true,
            'message' => $cancelledCount . " booking(s) cancelled"
        ]);
    }

    public function automaticBookingComplete()
    {
        $time = Carbon::now('Asia/Kolkata');

        $inprogressBooking = DB::table('booking')->where('status', 4)
            ->whereNotNull('accepted')
            ->get();
        // Check if there are any bookings to cancel
        if ($inprogressBooking->isEmpty()) {
            return new JsonResponse([
                'status' => false,
                'message' => "No bookings to complete"
            ]);
        }
        $completeCount = 0;
        foreach ($inprogressBooking as $booking) {
            $createdAt = Carbon::parse($booking->created_at, 'UTC')->timezone('Asia/Kolkata');
            $diff = $createdAt->diffInMinutes($time, false);
            if ($diff >= 60) {

                DB::table('booking')
                    ->where('id', $booking->id)
                    ->update(['status' => 2]);

                $completeCount++;
            }
        }
        return new JsonResponse([
            'status' => true,
            'message' => $completeCount . "bookings completed"
        ]);
    }
    public function maintainance()
    {
        $maintainance
            = site::where('id', 1)->first()->maintainance;
        return new JsonResponse([
            'status' => true,
            'maintainance' => $maintainance
        ]);
    }
    public function coupons()
    {
        $coupons = Coupon::where('status', 1)
            ->whereDate('expiry_date', '>', now())
            ->latest()
            ->get();
        $formattedCoupons = $coupons->map(function ($coupon) {
            return [
                'id' => $coupon->id,
                'title' => $coupon->title,
                'image' => $coupon->image,
                'type' => $coupon->type,
                'pincode_id' => $coupon->pincode_id,
                'code' => $coupon->code,
                'limit' => $coupon->limit,
                'is_multiple' => $coupon->is_multiple,
                'start_date' => $coupon->start_date->format('dM,Y'),
                'expiry_date' => $coupon->expiry_date->format('dM,Y'), // Format expiry_date here
                'discount_type' => $coupon->discount_type,
                'amount' => $coupon->amount,
                'percentage' => $coupon->percentage,
                'status' => $coupon->status,
                'created_by' => $coupon->created_by,
                'role' => $coupon->role,
                'pincode' => $coupon->pincode,
            ];
        });
        return new JsonResponse([
            'status' => true,
            'coupons' => $formattedCoupons
        ]);
    }

    public function couponList(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required',
            'userId' => 'required',
        ]);
        $user = User::where('user_id', $validated['userId'])->first();
        $nonMultipleUsageCoupon = Coupon::where('is_multiple', 0)
            ->get()
            ->pluck('id');
        // return $nonMultipleUsageCoupon;
        $usedCouponIds = Usedcoupon::where('user_id', $user->id)
            ->whereIn('coupon_id', $nonMultipleUsageCoupon)
            ->get()
            ->pluck('coupon_id');
        //  return $usedCouponIds;
        $coupons = Coupon::when($request->pincode, function ($query, $pincode) {
            $query->whereHas('pincode', function ($query) use ($pincode) {
                $query->where('pincode', 'LIKE', "$pincode%");
            });
        })
            ->whereNotIn('id', $usedCouponIds)
            ->where('type', $validated['type'])
            ->where('status', 1)
            ->where('limit', '>', 0)
            ->whereDate('start_date', '<=', now()->format('Y-m-d'))
            ->whereDate('expiry_date', '>=', now()->format('Y-m-d'))
            ->latest()
            ->with(['pincode', 'usedcoupon'])
            ->get();

        foreach ($coupons as $coupon) {
            // Check and update status for expired coupons
            if ($coupon->expiry_date->format('Y-m-d') < now()) {
                $coupon->update([
                    'status' => 0
                ]);
            }
        }
        // return $coupons;
        $formattedCoupons = $coupons->map(function ($coupon) {
            return [
                'id' => $coupon->id,
                'title' => $coupon->title,
                'image' => $coupon->image,
                'type' => $coupon->type,
                'pincode_id' => $coupon->pincode_id,
                'code' => $coupon->code,
                'limit' => $coupon->limit,
                'is_multiple' => $coupon->is_multiple,
                'start_date' => $coupon->start_date,
                'expiry_date' => $coupon->expiry_date->format('dM,Y'), // Format expiry_date here
                'discount_type' => $coupon->discount_type,
                'amount' => $coupon->amount,
                'percentage' => $coupon->percentage,
                'status' => $coupon->status,
                // 'created_at' => $coupon->created_at,
                // 'updated_at' => $coupon->updated_at,
                // 'created_by' => $coupon->created_by,
                // 'role' => $coupon->role,
                'pincode' => [
                    'id' => $coupon?->pincode?->id,
                    'state' => $coupon?->pincode?->state,
                    'district' => $coupon?->pincode?->district,
                    'city' => $coupon?->pincode?->city,
                    'taluk' => $coupon?->pincode?->taluk,
                    'pincode' => $coupon?->pincode?->pincode,
                    'usedBy' => $coupon?->pincode?->usedBy,
                    // 'created_at' => $coupon->pincode->created_at,
                    // 'updated_at' => $coupon->pincode->updated_at,
                ],
            ];
        });

        return new JsonResponse([
            'status' => true,
            'message' => $formattedCoupons
        ]);
    }

    public function appVerision()
    {
        $app_verision = site::where('id', 1)
            ->select(['user_app', 'driver_app'])
            ?->first();
        return new JsonResponse([
            'status' => true,
            'message' => $app_verision
        ]);
    }

    public function reportDriver(Request $request)
    {
        $validated = $request->validate([
            'report_id' => 'nullable',
            'booking_id' => ['required', Rule::exists(Booking::class, 'booking_id')],
            'driver_id' => 'required',
            'user_id' => 'required',
            'notes' => 'required',
        ]);
        $reportCount = Report::count();
        //return $reportCount;
        $validated['report_id'] = "RPT#00" . $reportCount + 1;
        //return $validated;
        $report = Report::create($validated);
        return new JsonResponse([
            'status' => true,
            'message' => 'Reported successfully, Your report Id is' . $report->report_id
        ]);
    }
    public function paymentStatus(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => ['required', Rule::exists(Booking::class, 'booking_id')],
        ]);
        $bookingPayment = BookingPayment::where('booking_id', $validated['booking_id'])->first();
        if ($bookingPayment->status == 1) {
            return new JsonResponse([
                'status' => true,
                'message' => 'paid'
            ]);
        } else {
            return new JsonResponse([
                'status' => false,
                'message' => 'unpaid'
            ]);
        }
    }

    public function bookingStatus(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => ['required', Rule::exists(Booking::class, 'booking_id')],
        ]);
        //return $validated;
        $booking = Booking::where('booking_id', $validated['booking_id'])->first();

        if ($booking->status == 2) {
            $bookingPayment = BookingPayment::where('booking_id', $validated['booking_id'])->first();
            if (isset($bookingPayment)) {
                if ($bookingPayment->status == 1) {
                    return new JsonResponse([
                        'status' => true,
                        'message' => 'Paid Successfully'
                    ]);
                } else {
                    return new JsonResponse([
                        'status' => false,
                        'message' => 'Unpaid'
                    ]);
                }
            } else {
                return new JsonResponse([
                    'status' => false,
                    'message' => 'Booking Not Found'
                ]);
            }
        } else {
            return new JsonResponse([
                'status' => false,
                'message' => 'Booking is Not Completed'
            ]);
        }
    }

    public function driverDocument(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => ['required', Rule::exists(Driver::class, 'userid')],
        ]);

        $driverDocument = Driver::where('userid', $validated['driver_id'])->first();

        if (isset($driverDocument)) {
            $driverDocuments = [
                'aadharFrontImage' => $driverDocument->aadharFrontImage,
                'aadharBackImage' => $driverDocument->aadharBackImage,
                'customerdocument' => $driverDocument->customerdocument,
                'drivingLicence' => $driverDocument->drivingLicence,
                'rcbook' => $driverDocument->rcbook,
                'insurance' => $driverDocument->insurance,
                'bike' => $driverDocument->bike,
            ];
            return new JsonResponse([
                'status' => true,
                'Driver Documents' => $driverDocuments
            ]);
        } else {
            return new JsonResponse([
                'status' => false,
                'message' => 'Driver Not Found'
            ]);
        }
    }

    public function rating(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|string',
            'driver_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'remarks' => 'nullable|string'
        ]);

        $booking = Booking::where('booking_id', $validated['booking_id'])
            ->where('accepted', $validated['driver_id'])
            ->first();
        if (!$booking) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Booking Not Found or Driver Not Assigned'
            ]);
        }

        if (in_array($booking->status, [2, 3])) {
            $ratingFound = BookingRating::where('booking_id', $validated['booking_id'])
                ->where('driver_id', $validated['driver_id'])
                ->first();

            if (!$ratingFound) {
                BookingRating::create([
                    'booking_id' => $validated['booking_id'],
                    'customer_id' => $booking->customer_id,
                    'driver_id' => $validated['driver_id'],
                    'rating' => $validated['rating'],
                    'remarks' => $validated['remarks'] ?? null,
                    'external_phone' => $booking->external_phone
                ]);
                return new JsonResponse([
                    'status' => true,
                    'message' => 'Rated Successfully'
                ]);
            } else {
                return new JsonResponse([
                    'status' => false,
                    'message' => 'Already Rated'
                ]);
            }
        } else {
            return new JsonResponse([
                'status' => false,
                'message' => "unable to rate now, Status MisMatch (must be completed/cancelled)"
            ]);
        }
    }

    public function activeRadius(Request $request)
    {
        $validated = $request->validate([
            'driver_user_id' => 'required',
            'is_radius' => 'required',
        ]);

        // Retrieve the Driver model instance using Eloquent
        $driver = Driver::where('userid', $validated['driver_user_id'])->first();

        if (!$driver) {
            return $this->sendError("Driver not found");
        }

        // Update the is_radius attribute of the driver
        $driver->is_radius = $validated['is_radius'] == 1 ? 1 : 0;
        $driver->update();

        if ($driver->is_radius == 1) {
            return new JsonResponse([
                'status' => true,
                'message' => "Radius Increased Successfully"
            ]);
        } else {
            return new JsonResponse([
                'status' => false,
                'message' => "Radius deactivated Successfully"
            ]);
        }
    }

    public function checkIsRadius(Request $request)
    {
        $validated = $request->validate([
            'driver_user_id' => 'required',
        ]);

        $driver = Driver::where('userid', $validated['driver_user_id'])->first();

        if (!$driver) {
            return $this->sendError("Driver not found");
        }

        if ($driver->is_radius == 1) {
            return new JsonResponse([
                'status' => true,
                'message' => "Active"
            ]);
        } else {
            return new JsonResponse([
                'status' => false,
                'message' => "Deactive"
            ]);
        }
    }

    public function googleSignUp(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'phone' => 'nullable|unique:users,phone',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'address1' => 'nullable',
            'address2' => 'nullable'
        ]);

        $input = $request->all();
        //$input['otp'] = rand(1000, 9999);
        $input['password'] = bcrypt($input['password']);
        $input['user_id'] = 'DK-' . uniqid();
        $input['is_googleUser'] = 1;
        $user = User::create($input);

        if ($request->has('address1')) {
            UserAddress::create([
                'user_id' => $user->user_id,
                'address1' => $request->input('address1'),
                'type' => '1'
            ]);
        }
        if ($request->has('address2')) {
            UserAddress::create([
                'user_id' => $user->user_id,
                'address1' => $request->input('address2'),
                'type' => '2'
            ]);
        }

        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;
        $success['user_id'] =  $user->user_id;
        $success['user_id'] =  $user->user_id;
        $success['id'] =  $user->id;
        return $this->sendResponse($success, 'User register successfully.');
    }

    public function googleSignIn(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required',
            'email' => 'required',
            'phone' => 'nullable',
            'device_token' => 'required',
        ]);

        if (isset($request->email) && (Auth::guard('api')->attempt(['email' => $request->email, 'password' => $request->password]))) {
            $user = Auth::guard('api')->user();
            // log::info('user details',[$user]);
            //return $user;
            if (($user->is_googleUser == 1) && ($user->blockedstatus == 1)) {
                $success['token'] =  $user->createToken('MyApp')->plainTextToken;
                $success['name'] =  $user->name;
                $success['user_id'] =  $user->user_id;
                $success['id'] =  $user->id;
                $user->last_login = date("Y-m-d H:i:s");
                $user->device_token = $request->device_token;
                $user->save();
                return $this->sendResponse($success, 'User login successfully.');
            } else {
                return $this->sendError('User Blocked', ['error' => 'Contact Admin'], 403);
            }
        } else {
            return $this->sendError('Wrong Credentials', ['error' => 'Unauthorised'], 401); // Explicitly setting status code to 401
        }
    }

    public function drivercurrentlocation(Request $request)
    {
        $validated = $request->validate([
            'userid' => 'required|exists:driver,userid',
            'lat' => 'required',
            'long' => 'required',
        ]);

        $driver = Driver::where('userid', $validated['userid'])?->first();

        if (isset($driver)) {
            $driver->lat = $validated['lat'];
            $driver->long = $validated['long'];
            $driver->update();
            $message = "Location Updated";
            return new JsonResponse([
                'status' => true,
                'message' => $message
            ]);
        } else {
            $message = "Driver Not Found";
            return new JsonResponse([
                'status' => false,
                'message' => $message
            ]);
        }
    }

    public function payment_status(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:booking,booking_id',
            'order_id' => 'required',
            'status' => 'required',
        ]);

        $booking = Booking::where('booking_id', $validated['booking_id'])->first();
        if (!$booking) {
            return new JsonResponse([
                'status' => false,
                'message' => "Booking Not Found"
            ]);
        }

        $bookingPayment = BookingPayment::where('booking_id', $validated['booking_id'])
            ->first();
        if ($bookingPayment) {
            $bookingPayment->order_id = $validated['order_id'];
            $bookingPayment->status = $validated['status'];
            $bookingPayment->save();

            return new JsonResponse([
                'status' => true,
                'message' => "Booking Status Updated Successfully"
            ]);
        } else {
            return new JsonResponse([
                'status' => false,
                'message' => "Booking Payment Not Found"
            ]);
        }
    }

    public function driverStatus(Request $request)
    {
        $validated = $request->validate([
            // 'source' => 'required|in:0,1',
            'user_id' => 'required_if:source,0|nullable|exists:users,id',
            'external_phone' => 'required_if:source,1|nullable|string',
            'is_live' => 'required'
        ]);

        $user = Enduser::where('id', $validated['user_id'] ?? null)
            ->orWhere('phone', $validated['external_phone'] ?? null)
            ->first();

        if (!$user) {
            return new JsonResponse([
                'status' => false,
                'message' => "User Not Found"
            ]);
        }

        if ($user->is_driver == 1) {
            $driver = Driver::where('userid', $user->id)->first();
            if ($driver && $driver->status == 1) {
                $user->is_live = $validated['is_live'] == 1 ? 1 : 0;
                $user->update();
                $currentStatus = Enduser::where('id', $user->id)->first();
                if ($currentStatus->is_live == 0) {
                    return new JsonResponse([
                        'status' => false,
                        'message' => "Bye Bye"
                    ]);
                } else {
                    return new JsonResponse([
                        'status' => true,
                        'message' => "Welcome Back"
                    ]);
                }
            } else {
                return new JsonResponse([
                    'status' => false,
                    'message' => "Unable to change the status"
                ]);
            }
        } else {
            return new JsonResponse([
                'status' => false,
                'message' => "Not a driver"
            ]);
        }
    }

    public function currentStatus(Request $request)
    {
        $validated = $request->validate([
            // 'source' => 'required|in:0,1',
            'user_id' => 'required_if:source,0|nullable|exists:users,id',
            'external_phone' => 'required_if:source,1|nullable|string',
        ]);

        if (empty($validated['user_id']) && empty($validated['external_phone'])) {
            return new JsonResponse([
                'status' => false,
                'user_details' => null,
                'message' => 'user_id or external_phone is required',
            ]);
        }

        $user = Enduser::query()
            ->when(!empty($validated['user_id']), function ($query) use ($validated) {
                $query->where('id', $validated['user_id']);
            })
            ->when(empty($validated['user_id']) && !empty($validated['external_phone']), function ($query) use ($validated) {
                $query->where('phone', $validated['external_phone']);
            })
            ->select(['id', 'user_id', 'is_driver', 'blockedstatus'])
            ->first();

        if (!$user) {
            return new JsonResponse([
                'status' => false,
                'user_details' => null
            ]);
        }

        $currentStatus = 0;
        $reasonDetails = null;
        $statusText = null;

        if ($user->is_driver == 0) {
            $reason = EnduserReason::where('user_id', $user->user_id)
                ->select(['status', 'reason'])
                ->latest()
                ->first();

            $currentStatus = $user->blockedstatus == 1 ? 0 : 1;
            $currentStatusText = $currentStatus == 1 ? 'blocked' : 'unblocked';

            if (isset($reason)) {
                $reasonDetails = [
                    'reason' => $reason->reason,
                    'status' => $currentStatusText,
                ];
            }

            return new JsonResponse([
                'status' => true,
                'user_details' => [
                    'id' => $user->id,
                    'user_id' => $user->user_id,
                    'currentStatus' => $currentStatus,
                    'is_driver' => $user->is_driver,
                    'reason' => $reasonDetails,
                ],
            ]);
        } else {
            $driver = Driver::where('userid', $user->id)->first();
            if ($driver) {
                if ($driver->status == 1) {
                    $reason = Unblocklist::where('unblockedId', $driver->id)->where('table', 'driver')->latest()->first();
                    $statusText = 'Unblock';
                    $currentStatus = 0;
                    $reasonDetails = $reason?->comments ?? 'Unblock';
                } elseif ($driver->status == 2) {
                    $reason = Blocklist::where('blockedId', $driver->id)->where('table', 'driver')->latest()->first();
                    $statusText = 'Block';
                    $currentStatus = 1;
                    $reasonDetails = $reason?->comments ?? 'Your account has been blocked due to policy violation';
                } else {
                    $statusText = 'Driver Status Off Now';
                    $currentStatus = 2;
                    $reasonDetails = 'Driver Status Off Now';
                }
            }
        }

        return new JsonResponse([
            'status' => true,
            'user_details' => [
                'id' => $user->id,
                'user_id' => $user->user_id,
                'is_driver' => $user->is_driver,
                'reason' => $reasonDetails,
                'status' => $statusText,
                'currentStatus' => $currentStatus,
            ],
        ]);
    }

    public function forgot(ForgotPassword $request)
    {
        $validator = $request->validated();
        $user = User::where('phone', $validator['phone'])->first();
        $password = $validator['password'];
        $hashedPassword = Hash::make($password);
        //return $hashedPassword;
        $user['password'] = $hashedPassword;
        $user->update();

        return new JsonResponse([
            'status' => true,
            'message' => 'Password Changed Succesfully'
        ]);
    }

    public function driverprofile(Request $d)
    {
        if ($d->get('id') == "") {
            return $this->sendError("Id is required");
        } else {
            $val = Driver::join('users', "users.id", "=", "driver.userid")->where('driver.userid', $d->id)->get(['driver.name', 'driver.mobile', 'driver.email', 'users.gender', 'users.dob as DOB', 'users.image']);
            $bookingCompletedCount = Booking::where('accepted', $d->id)->where('status', 2)->get()->count();
            $totalDistanceTravelled = Booking::where('accepted', $d->id)->where('status', 2)->get()->sum('distance');
            $totalMinutesTravelled = Booking::where('accepted', $d->id)->where('status', 2)->get()->sum('duration');
            $val['bookingCompletedCount'] = $bookingCompletedCount;
            $val['totalDistanceTravelled'] = $totalDistanceTravelled;
            $val['totalMinutesTravelled'] = $totalMinutesTravelled;
            $totalRatings = BookingRating::where('driver_id', $d?->id)->get();
            $totalEarnings = Booking::with('bookingPayment')
                ->where('accepted', $d->get('id'))
                ->where('status', 2)
                ->get()
                ->sum(function ($booking) {
                    return $booking->bookingPayment->sum('total'); // assuming 'payment_amount' is the column name
                });
            //return $totalEarnings;
            if ($totalEarnings > 0) {
                $val['totalEarnings'] = $totalEarnings;
            } else {
                $val['totalEarnings'] = 0;
            }
            if (count($totalRatings) > 0) {
                $ratingCount = $totalRatings->count();
                $ratingPoints = $totalRatings->sum('rating');
                $averageRating = $ratingPoints / $ratingCount;
                $val['overAllRating'] = round($averageRating, 1);
            } else {
                $val['overAllRating'] = 0;
            }
            return $this->sendResponse($val, "Driver Profile Is Retreived successfully");
        }
    }

    public function drivercall(Request $d)
    {
        if ($d->get('id') == "") {
            return $this->sendError("Id is required");
        } else {
            $val = Driver::join('subscriber', "subscriber.id", "=", "driver.subscriberId")->where('driver.userid', $d->get('id'))->get(['driver.mobile']);
            return $this->sendResponse($val, "Driver Phone number Retrived successfully");
        }
    }

    public function subscribercall(Request $d)
    {
        if ($d->get('id') == "") {
            return $this->sendError("Id is required");
        } else {
            $val = Driver::join('subscriber', "subscriber.id", "=", "driver.subscriberId")->where('driver.userid', $d->get('id'))->get(['subscriber.mobile']);
            return $this->sendResponse($val, "Subscriber Phone number Retrived successfully");
        }
    }

    public function bookingtaxi(Request $d)
    {
        // source, company_id injected by detect.company middleware if X-API-Key present
        $source = (int) $d->get('source', 0);

        $validate = Validator::make($d->all(), [
            'distance' => "required",
            'duration' => "required",
            'pincode' => "required",
            'category' => "required",
            'description' => "required",
            'from_location' => "required|array",
            'to_location' => "required|array",
            'payment_method' => "required",

            'title' => "nullable",
            'content' => "nullable",
            "coupon_id" => "nullable",
            "coupon_amount" => "nullable",

            // Conditional fields based on source
            'user_id' => $source == 0 ? "required|string|exists:users,user_id" : "nullable",
            'external_name' => $source == 1 ? "required|string" : "nullable",
            'external_phone' => $source == 1 ? "required|string" : "nullable",
            //'company_id' => $source == 1 ? "required|integer|exists:companies,id" : "nullable",
        ]);

        if ($validate->fails()) {
            return $this->sendError($validate->errors());
        }

        // external + user_id not allowed
        if ($source == 1 && $d->get('user_id')) {
            return $this->sendError("Do not send user_id for external booking");
        }

        // donkey + no user_id not allowed
        if ($source == 0 && !$d->get('user_id')) {
            return $this->sendError("user_id required for donkey booking");
        }

        $customer_id = null;
        $company_id = null;
        $external_name = null;
        $external_phone = null;

        if ($source == 0) {
            $user = User::where('user_id', $d->get('user_id'))->first();
            if (!$user) {
                return $this->sendError("User not found");
            }
            $customer_id = $d->get('user_id');
        } else {
            if (!$d->get('external_phone')) {
                return $this->sendError("external_phone required");
            }
            // if (!$d->get('company_id')) {
            //     return $this->sendError("company_id required");
            // }

            $company_id = $d->get('company_id');
            $external_name = $d->get('external_name');
            $external_phone = $d->get('external_phone');
        }

        $pincode = Pincode::where('pincode', $d->get('pincode'))->first();
        if (!$pincode) {
            return $this->sendError("Invalid pincode");
        }

        $subscriber = Subscriber::where('id', $pincode->usedBy)->first();
        if (!$subscriber || $subscriber->blockedstatus != 1) {
            return response()->json([
                'status' => false,
                'message' => "Unable to make booking, subscriber blocked"
            ]);
        }

        $booking_id = 'doc-' . $this->guidv4();
        $from_location_id = 'loc-' . time() . '-' . mt_rand();
        $to_location_id = 'loc-' . time() . '-' . mt_rand();
        $otp = rand(1000, 9999);

        DB::transaction(function () use (
            $d,
            $booking_id,
            $from_location_id,
            $to_location_id,
            $otp,
            $subscriber,
            $customer_id,
            $external_name,
            $external_phone,
            $company_id,
            $source
        ) {

            DB::table('booking')->insert([
                "booking_id" => $booking_id,
                "customer_id" => $customer_id,
                "company_id" => $company_id,
                "assigned_subscriber_id" => $source === 1 ? $subscriber->id : null,
                "external_name" => $external_name,
                "external_phone" => $external_phone,
                "source" => $source,
                "status" => 0,
                "category" => $d->get('category'),
                "distance" => $d->get('distance'),
                "duration" => $d->get('duration'),
                "pincode" => $d->get('pincode'),
                "otp" => $otp,
                "title" => $d->get('title'),
                "content" => $d->get('content')
            ]);

            DB::table('booking_history')->insert([
                "booking_id" => $booking_id,
                "user_id" => $customer_id,
                "action" => "BOOKING CREATE",
                "description" => $d->get('description'),
                "type" => 0,
            ]);

            // **Insert From Location**
            $from_location = $d->get('from_location');
            DB::table('booking_locations')->insert([
                "booking_id" => $booking_id,
                "location_id" => $from_location_id,
                "address1" => $from_location['address1'] ?? null,
                "address2" => $from_location['address2'] ?? null,
                "address3" => $from_location['address3'] ?? null,
                "city" => $from_location['city'] ?? null,
                "state" => $from_location['state'] ?? null,
                "country" => $from_location['country'] ?? null,
                "postal_code" => $from_location['postal_code'] ?? null,
                "lat" => $from_location['lat'] ?? null,
                "long" => $from_location['long'] ?? null,
                "landmark" => $from_location['landmark'] ?? null,
            ]);

            // **Insert To Location**
            $to_location = $d->get('to_location');
            $address1 = [];
            $address2 = [];
            $address3 = [];
            $city = [];
            $state = [];
            $country = [];
            $postal_code = [];
            $lat = [];
            $long = [];
            $landmark = [];

            foreach ($to_location as $to_loc) {
                array_push($address1, $to_loc['address1'] ?? null);
                array_push($address2, $to_loc['address2'] ?? null);
                array_push($address3, $to_loc['address3'] ?? null);
                array_push($city, $to_loc['city'] ?? null);
                array_push($state, $to_loc['state'] ?? null);
                array_push($country, $to_loc['country'] ?? null);
                array_push($postal_code, $to_loc['postal_code'] ?? null);
                array_push($lat, $to_loc['lat'] ?? null);
                array_push($long, $to_loc['long'] ?? null);
                array_push($landmark, $to_loc['landmark'] ?? null);
            }

            DB::table('booking_locations')->insert([
                "booking_id" => $booking_id,
                "location_id" => $to_location_id,
                "address1" => implode(',', $address1),
                "address2" => implode(',', $address2),
                "address3" => implode(',', $address3),
                "city" => implode(',', $city),
                "state" => implode(',', $state),
                "country" => implode(',', $country),
                "postal_code" => implode(',', $postal_code),
                "lat" => implode(',', $lat),
                "long" => implode(',', $long),
                "landmark" => implode(',', $landmark),
            ]);

            // **Location Mapping**
            DB::table('booking_location_mapping')->insert([
                "booking_id" => $booking_id,
                "end_location_id" => $to_location_id,
                "start_location_id" => $from_location_id,
            ]);

            // **Calculate Price**
            $distance = $d->get('distance');
            $category = $d->get('category');
            $pincode = $d->get('pincode');

            $price = DB::table('price')->where('category', $category)->where('pincode', $pincode)
                ->where('range_from', '<=', $distance)
                ->where('range_to', '>=', $distance)
                ->first();

            if (!$price) {
                throw new \Exception("Price not found for given category and distance");
            }

            $service_cost = match ($category) {
                1 => $subscriber->biketaxi_price ?? 2,
                2 => $subscriber->pickup_price ?? 2,
                3 => $subscriber->buy_price ?? 2,
                4 => $subscriber->auto_price ?? 2,
                5 => $subscriber->cab_price ?? 2,
                default => 2
            };

            $tax = 0.18 * ($service_cost + ($price->amount * $distance));
            $base = round(($price->amount * $distance));
            $total = round($tax + $service_cost + $base);

            DB::table('booking_payment')->insert([
                "type" => $d->get('payment_method'),
                "booking_id" => $booking_id,
                "base_price" => $base,
                "tax" => $tax,
                "total" => $total,
                "service_cost" => $service_cost,
                "coupon_amount" => $d->get('coupon_amount') ?: null,
                "coupon_id" => $d->get('coupon_id') && is_numeric($d->get('coupon_id')) ? (int) $d->get('coupon_id') : null, // ✅ FIXED HERE
            ]);

            // External bookings are assigned to subscriber at creation.
            // Notify drivers only for internal donkey flow.
            if ($source === 0) {
                $this->notificationtodriverfirebase($pincode, [$booking_id, $from_location_id, $to_location_id, $d->get('user_id'), $distance, $pincode], $category);
            }
        });

        return $this->sendResponse([
            "otp" => $otp,
            "booking_id" => $booking_id
        ], "Booking has been successful");
    }

    public function bookingtaxibackup(Request $d)
    {
        //return $d;
        // return Hash::make('hai');
        $validate = Validator::make($d->all(), [
            'user_id' => "required",
            'distance' => "required",
            'duration' => "required",
            'pincode' => "required",
            'category' => "required",
            'description' => "required",
            'from_location' => "required",
            'to_location' => "required",
            // 'address1' => "required",
            // 'address2' => "required",
            // 'category' => "required",
            // 'city' => "required",
            // 'state' => "required",
            // 'country' => "required",
            // 'postal_code' => "required",
            // 'lat' => "required",
            // 'long' => "required",
            // 'landmark' => "required",
            'payment_method' => "required",
            'title' => "nullable",
            'content' => "nullable",
            "coupon_id" => "nullable",
            "coupon_amount" => "nullable"
        ]);
        //return $validate;
        if ($validate->fails()) {
            return $this->sendError($validate->errors());
        } else {
            $user = User::where('user_id', $d?->get('user_id'))?->first();
            $usedCoupon = Usedcoupon::where('user_id', $user?->id)
                ->where('coupon_id', $d?->get('coupon_id'))
                ?->first();
            $coupon = Coupon::where('id', $d?->get('coupon_id'))
                ?->first();
            //return $coupon;
            if (($d->get('coupon_id') != Null) && ($d->get('coupon_amount') != Null)) {
                if (isset($usedCoupon) && $coupon->is_multiple == 0) {
                    return new JsonResponse([
                        'status' => false,
                        'message' => "unable to make booking with these coupon."
                    ]);
                }

                if (isset($coupon)) {
                    if ($coupon->limit == 0) {
                        return new JsonResponse([
                            'status' => false,
                            'message' => "Coupon Limit Reached"
                        ]);
                    }
                } else {
                    return new JsonResponse([
                        'status' => false,
                        'message' => "Unknown Coupon"
                    ]);
                }
            }
            //return "hlo";
            $pincode = Pincode::where('pincode', $d?->get('pincode'))?->first();
            $subscriber = Subscriber::where('id', $pincode?->usedBy)?->first();
            if ($subscriber->blockedstatus == 1) {
                $checkthebookinghistory1 = DB::table('booking')->where('customer_id', $d->get('user_id'))->orderBy('created_at', 'desc')->get();

                if (count($checkthebookinghistory1) > 0) {
                    $checkthebookinghistory = DB::table('booking')->where('customer_id', $d->get('user_id'))->orderBy('created_at', 'desc')->first();

                    if ($checkthebookinghistory->status == 4 || $checkthebookinghistory->status == 1 || $checkthebookinghistory->status == 0) {
                        return $this->sendError("Sorry You Have Already Booked", array("bookingallowingstatus" => 1));
                    }
                }

                $booking_id = 'doc-' . $this->guidv4();
                $from_location_id = 'loc-' . time() . '-' . mt_rand();
                $to_location_id = 'loc-' . time() . '-' . mt_rand();
                $notification_data = array($booking_id, $from_location_id, $to_location_id, $d->get('user_id'), $d->get('distance'), $d->get('pincode'));
                $otp = rand(1000, 9999);
                DB::table('booking')->insert([
                    "booking_id" => $booking_id,
                    "customer_id" => $d->get('user_id'),
                    "status" => 0,
                    "category" => $d->get('category'),
                    "distance" => $d->get('distance'),
                    "duration" => $d->get('duration'),
                    "pincode" => $d->get('pincode'),
                    "otp" => $otp,
                    "title" => $d->get('title'),
                    "content" => $d->get('content')
                ]);
                DB::table('booking_history')->insert([
                    "booking_id" => $booking_id,
                    "user_id" => $d->get('user_id'),
                    "action" => "BOOKING CREATE",
                    "description" => $d->get('description'),
                    "type" => 0,
                    // "pincode" => $d->get('pincode'),
                ]);
                // $address1[] = $d->get('address1');
                // $address2[] = $d->get('address2');
                // $city[] = $d->get('city');
                // $state[] = $d->get('state');
                // $country[] = $d->get('country');
                // $postal_code[] = $d->get('postal_code');
                // $lat[] = $d->get('lat');
                // $long[] = $d->get('long');
                // $landmark[] = $d->get('landmark');
                DB::table('booking_locations')->insert([
                    "booking_id" => $booking_id,
                    "location_id" => $from_location_id,
                    "address1" => $d->get('from_location')['address1'],
                    "address2" => $d->get('from_location')['address2'],
                    "address3" => $d->get('from_location')['address3'],
                    // "from_location" => $d->get('from_location'),
                    // "to_location" => $d->get('to_location'),
                    "city" => $d->get('from_location')['city'],
                    "state" => $d->get('from_location')['state'],
                    "country" => $d->get('from_location')['country'],
                    "postal_code" => $d->get('from_location')['postal_code'],
                    "lat" => $d->get('from_location')['lat'],
                    "long" => $d->get('from_location')['long'],
                    "landmark" => $d->get('from_location')['landmark'],
                ]);
                $to_location = $d->get('to_location');
                $address1 = [];
                $address2 = [];
                $city = [];
                $address3 = [];
                $state = [];
                $country = [];
                $postal_code = [];
                $lat = [];
                $long = [];
                $landmark = [];
                foreach ($to_location as $to_loc) {
                    // return $to_loc['address1'];
                    array_push($address1, $to_loc['address1']);
                    array_push($address2, $to_loc['address2']);
                    array_push($address3, $to_loc['address3']);
                    array_push($city, $to_loc['city']);
                    array_push($state, $to_loc['state']);
                    array_push($country, $to_loc['country']);
                    array_push($postal_code, $to_loc['postal_code']);
                    array_push($lat, $to_loc['lat']);
                    array_push($long, $to_loc['long']);
                    array_push($landmark, $to_loc['landmark']);
                }
                DB::table('booking_locations')->insert([
                    "booking_id" => $booking_id,
                    "location_id" => $to_location_id,
                    // "from_location" => $d->get('from_location'),
                    // "to_location" => $d->get('to_location'),
                    // "address1" => $d->get('address1'),
                    // "address2" => $d->get('address2'),
                    // "address3" => $d->get('address2'),
                    // "city" => $d->get('city'),
                    // "state" => $d->get('state'),
                    // "country" => $d->get('country'),
                    // "postal_code" => $d->get('postal_code'),
                    // "lat" => $d->get('lat'),
                    // "long" => $d->get('long'),
                    // "landmark" => $d->get('landmark'),
                    "address1" => implode(',', $address1),
                    "address2" => implode(',', $address2),
                    "address3" => implode(',', $address3),

                    "city" => implode(',', $city),
                    "state" => implode(',', $state),
                    "country" => implode(',', $country),
                    "postal_code" => implode(',', $postal_code),
                    "lat" => implode(',', $lat),
                    "long" => implode(',', $long),
                    "landmark" => implode(',', $landmark),
                ]);

                DB::table('booking_location_mapping')->insert([
                    "booking_id" => $booking_id,
                    "end_location_id" => $to_location_id,
                    "start_location_id" => $from_location_id,

                ]);
                // $price = DB::table('price')->where('pincode', $d->get('pincode'))->where('category', $d->get('category'))->where('range_from', "<=", $d->get('distance'))->where('range_to', ">", $d->get('distance'))->get();

                $distance = $d->get('distance');
                $category = $d->get('category');
                $pincode = $d->get('pincode');
                $price =  DB::table('price')->where(['category' => $category, 'pincode' => $pincode])
                    ->where('range_from', '<', $distance)
                    ->where('range_to', '>=', $distance)
                    ->get();
                // $total = round(($price[0]->amount*$d->get('distance')) +((($price[0]->amount*$d->get('distance'))/100)* $price[0]->tax));
                // $total=$this->calculatetotal(($price[0]->amount*$d->get('distance')),$d->get('category'),$price[0]->subscriber_id);
                $pincodeDetails = Pincode::where('pincode', $d->get('pincode'))?->first();
                $subscriber = Subscriber::where('id', $pincodeDetails->usedBy)?->first();
                //return $subscriber;
                $service_cost = match ((int)$d->get('category')) {
                    1 => $subscriber?->biketaxi_price ?? 2,
                    2 => $subscriber?->pickup_price ?? 2,
                    3 => $subscriber?->buy_price ?? 2,
                    4 => $subscriber?->auto_price ?? 2,
                    5 => $subscriber?->cab_price ?? 2,
                    default => 2
                };
                //return $service_cost;
                //return round(($price[0]->amount * $d->get('distance')));
                $tax = 23 / 100 * ($service_cost + ($price[0]->amount * $d->get('distance')));
                //return $tax;
                $base = round(($price[0]->amount * $d->get('distance')));
                //return round($tax + $service_cost + $base);
                DB::table('booking_payment')->insert([
                    "type" => $d->get('payment_method'),
                    "booking_id" => $booking_id,
                    "base_price" => $base,
                    // "tax" => $price[0]->tax,
                    //"tax" => round($this->calculatetotaltax(round(($price[0]->amount * $d->get('distance'))), $d->get('category'), $price[0]->subscriber_id)),
                    "tax" => $tax,
                    "tax_split_1" => $price[0]->tax_split_1,
                    "tax_split_2" => $price[0]->tax_split_2,
                    // "total" => $total,
                    //"total" => round($this->calculatetotal(round(($price[0]->amount * $d->get('distance'))), $d->get('category'), $price[0]->subscriber_id)),
                    "total" => round($tax + $service_cost + $base),
                    "service_cost" => $service_cost,
                    // "round_off" => $round_off,
                    "coupon_amount" => $d->get('coupon_amount') == null ? null : $d->get('coupon_amount'),
                    "coupon_id" => $d->get('coupon_id') == null ? null : $coupon->id,
                ]);

                if (isset($coupon) && ($d->get('coupon_id') != null)) {
                    Usedcoupon::create([
                        "user_id" => $user->id,
                        "coupon_id" => $coupon->id,
                    ]);
                    if ($coupon->limit > 0) {
                        $coupon->limit = $coupon->limit - 1;
                        $coupon->update();
                    }
                }
                $this->notificationtodriverfirebase($d->get('pincode'), $notification_data, $d->get('category'));
                // return array(["data" => $notification_data]);
                $data['otp'] = $otp;
                $data['booking_id'] = $booking_id;
                $data["bookingallowingstatus"] = 0;
                return $this->sendResponse($data, "Booking Has Been Successfull");
            } else {
                return new JsonResponse([
                    'status' => false,
                    'message' => "unable to make booking, subscriber Blocked Currently"
                ]);
            }
        }
        // return $this->sendResponse($otp, "Booking Has Been Successfull");
    }



    public function calculatetotal($price, $category, $id)
    {

        $subscriber = Subscriber::where('id', $id)->first();
        if ($category == 1) {
            $calprice = $price + $subscriber->biketaxi_price;
        }
        if ($category == 2) {
            $calprice = $price + $subscriber->pickup_price;
        }
        if ($category == 3) {
            $calprice = $price + $subscriber->buy_price;
        }

        return $total = $calprice + (($calprice / 100) * 2.5) + (($calprice / 100) * 5);
    }
    public function calculatetotaltax($price, $category, $id)
    {

        $subscriber = Subscriber::where('id', $id)->first();
        if ($category == 1) {
            $calprice = $price + $subscriber->biketaxi_price;
            $t = $subscriber->biketaxi_price;
        }
        if ($category == 2) {
            $calprice = $price + $subscriber->pickup_price;
            $t = $subscriber->pickup_price;
        }
        if ($category == 3) {
            $calprice = $price + $subscriber->buy_price;
            $t = $subscriber->buy_price;
        }
        // return $price;
        return $tax = (($calprice / 100) * 11) + (($calprice / 100) * 12) + $t;
    }

    public function logoutapi(Request $d)
    {
        $validator = Validator::make($d->all(), [
            'user_id' => 'required',

        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        } else {
            DB::table('users')->where('user_id', $d->user_id)->update([
                "logout_time" => date("Y-m-d H:i:s"),
            ]);
            return $this->sendResponse("", "Logout Successfully");
        }
    }

    public function notificationtodriverfirebase($pincode1, $notification_data, $category)
    {
        if ($category == 4) {
            $type = 2;
        } elseif ($category == 5) {
            $type = 3;
        } else {
            $type = 1;
        }
        $driverarray = [];
        $pincode = $pincode1;
        $Pincode = Pincode::where('pincode', $pincode)->get();
        foreach ($Pincode as $pin) {
            if ($pin->pincode == $pincode) {

                $driver = Driver::where('type', $type)->get(['pincode', 'id', 'userid', 'type']);
                foreach ($driver as $drivers) {
                    $driverpin =  json_decode($drivers->pincode);
                    foreach ($driverpin as $p) {
                        if ($p == $pin->id) {
                            //notification for that driver pincode
                            // $user_id = User::where('id', $drivers->id)->get(['user_id']);
                            // if (count($user_id) > 0)
                            array_push($driverarray, $drivers->userid);
                        }
                    }
                }
            }
        }
        $driverarray = array_unique($driverarray);
        if (count($driverarray) > 0) {
            // return $this->sendResponse(array_unique($driverarray), 'These Are the driver id for the notification');
            foreach ($driverarray as $da) {
                $d11 = User::where('id', $da)->get(['device_token']);
                if (count($d11) > 0 && $d11[0]->device_token != "") {
                    $url = 'https://fcm.googleapis.com/v1/projects/donkey-driver/messages:send';
                    // Authorization token (replace with your actual bearer token)
                    $driverToken = site::where('id', 1)?->first()?->driverToken;
                    $token = $driverToken;

                    // Compile headers in one variable
                    $headers = array(
                        'Authorization: ' . $token,
                        'Content-Type: application/json'
                    );

                    // Notification payload
                    $notifData = [
                        'title' => "Duty Alert !!",
                        'body' => "Passenger needs a ride \nDistance: " . $notification_data[4] . "\nPincode: " . $notification_data[5]
                    ];

                    // Data payload (extra data)
                    $dataPayload = [
                        'title' => "Duty Alert !!",
                        'body' => "Passenger needs a ride \nDistance: " . $notification_data[4] . "\nPincode: " . $notification_data[5],
                        'story_id' => "story_12345"
                    ];

                    $apiBody = [
                        'message' => [
                            'token' => $d11[0]->device_token, // Target device token
                            'notification' => $notifData,  // Notification section
                            'android' => [
                                'priority' => 'high',
                                'notification' => [
                                    'sound' => 'default',
                                    'channel_id' => '1101'
                                ]
                            ],
                            'apns' => [
                                'headers' => [
                                    'apns-priority' => '10', // iOS notification priority
                                ],
                            ],
                            'data' => $dataPayload // Data section
                        ]
                    ];

                    // Initialize CURL and set options
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));

                    // Execute the call and save the response
                    $result = curl_exec($ch);

                    // Capture any CURL error
                    if (curl_errno($ch)) {
                        $error_msg = curl_error($ch);
                        Log::error("CURL error: " . $error_msg);
                    } else {
                        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        Log::info("Response code: " . $responseCode);
                    }

                    // Log the entire result and response for debugging
                    Log::info("FCM Response: " . $result);

                    // Close CURL
                    curl_close($ch);
                }
            }
        }
        return 1;
    }

    public function pincheckNew(Request $request)
    {
        $validated = $request->validate([
            'pincode' => 'required',
            'category' => 'required',
        ]);
        // $p=Hash::make($request->password);

        $data = [];
        $service = DB::table('price')->where('pincode', $validated['pincode'])->where('category', $validated['category'])->count();
        if ($service > 0) {
            $pincodeData = DB::table('pincode')->where('pincode', $validated['pincode'])?->first();
            $subscriber = DB::table('subscriber')->where('id', $pincodeData?->usedBy)?->first();
            if (($subscriber?->activestatus == 1) && ($subscriber?->blockedstatus == 1)) {
                $data = $pincodeData;
                return $this->sendResponse($data, 'This pincode is  available.');
            } else {
                return $this->sendError('Pincode Not Available Please Enter Another Pincode.');
            }
        } else {
            return $this->sendError('Pincode Not Available Please Enter Another Pincode.');
        }
    }

    public function pincheck01(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pincode' => 'required',
            'category' => 'required'

        ]);
        // $p=Hash::make($request->password);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $data = [];
            $c = DB::table('price')->where('pincode', $request->input('pincode'))->where('category', $request->input('category'))->count();
            if ($c > 0) {
                $data = DB::table('pincode')->where('pincode', $request->input('pincode'))->get();
                $subscriber = Subscriber::where('id', $data[0]->usedBy)->first();
                if (($subscriber->activestatus != 1) || ($subscriber->blockedstatus != 1)) {
                    return $this->sendError('Pincode Not Available Please Enter Another Pincode.');
                }
                $pincode = Pincode::where('pincode', $request->pincode)?->first();
                $pincodebasedcategory = Pincodebasedcategory::where('pincode_id', $pincode?->id)
                    ?->where("category_id", $request?->category)
                    ?->first();
                $category = Category::where('id', $request->category)
                    ?->first();
                //if(($pincodebasedcategory->status == 0) || ($category->status == 0))
                //{
                //   return $this->sendError('Service Not Available in this Pincode.');
                //}
                return $this->sendResponse($data, 'This pincode is  available.');
            } else {
                return $this->sendError('Pincode Not Available Please Enter Another Pincode.');
            }
        }
    }

    public function pincheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pincode' => 'required',
            'category' => 'required'

        ]);
        // $p=Hash::make($request->password);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $data = [];
            $c = DB::table('price')->where('pincode', $request->input('pincode'))->where('category', $request->input('category'))->count();
            if ($c > 0) {
                $data = DB::table('pincode')->where('pincode', $request->input('pincode'))->get();
                $subscriber = Subscriber::where('id', $data[0]->usedBy)->first();
                if (($subscriber->activestatus != 1) || ($subscriber->blockedstatus != 1)) {
                    return $this->sendError('Pincode Not Available Please Enter Another Pincode.');
                }
                $pincode = Pincode::where('pincode', $request->pincode)?->first();
                $cat = "";
                if ($request->input('category') == 4) {
                    $cat = 2;
                } elseif ($request->input('category') == 5) {
                    $cat = 3;
                } else {
                    $cat = 1;
                }
                $drivers = Driver::where('type', $cat)
                    ->where('pincode', 'Like', "%$pincode?->id%")
                    ->get();
                $pincodebasedcategory = Pincodebasedcategory::where('pincode_id', $pincode?->id)
                    ?->where("category_id", $request?->category)
                    ?->first();
                $category = Category::where('id', $request->category)
                    ?->first();
                if (($pincodebasedcategory->status == 0) || ($category->status == 0)) {
                    return $this->sendError('Service Not Available in this Pincode.');
                }
                if (count($drivers) > 0) {
                    return $this->sendResponse($data, 'This pincode is  available.');
                } else {
                    return $this->sendError('Pincode Not Available Please Enter Another Pincode.');
                }
            } else {
                return $this->sendError('Pincode Not Available Please Enter Another Pincode.');
            }
        }
    }

    public function getlivelocation(Request $d)
    {
        $data['latitude'] = \Location::get()->latitude;
        $data['longitude'] = \Location::get()->longitude;
        return $this->sendResponse($data, 'Live Latitude and Longitude');
    }
    public function banner(Request $request)
    {
        $banners = banner::latest()->get();
        return $this->sendResponse($banners, 'Banner Content');
    }
    public function voucher(Request $d)
    {
        $url = env('APP_URL');
        $vouchers = Voucher::get('images');
        foreach ($vouchers as $ban => $value) {

            $data['vouchers'][$ban] = $url . 'public/admin/voucher/' . $value['images'];
        }
        // $banner=banner::get();
        // $data['banners']=banner::get('images');
        return $this->sendResponse($data, 'Vouchers Images');
    }

    public function bookingaccept(Request $request)
    {
        $isExternal = $this->isExternalBookingRequest($request, 'external.bookingaccept');
        $request->merge(['source' => $isExternal ? 1 : (int) $request->get('source', 0)]);

        $validator = Validator::make($request->all(), [
            'source' => 'nullable|in:0,1',
            'user_id' => $isExternal ? 'nullable' : 'required',
            'external_phone' => $isExternal ? 'required' : 'nullable',
            'booking_id' => 'required',
            'driver_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $bookingModel = Booking::where('booking_id', $request->booking_id)->first();
        if (!$bookingModel || isset($bookingModel->accepted) || $bookingModel->status != 0) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Cannot accept booking'
            ]);
        }

        $driver = Driver::where('userid', $request->driver_id)->first();
        if (!$driver) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Driver not found'
            ]);
        }

        if ((int) $bookingModel->source === 1) {
            if (empty($bookingModel->provider_accepted_at)) {
                return new JsonResponse([
                    'status' => false,
                    'message' => 'Provider must accept booking before driver assignment'
                ]);
            }

            if (!empty($bookingModel->assigned_subscriber_id) && (int) $driver->subscriberId !== (int) $bookingModel->assigned_subscriber_id) {
                return new JsonResponse([
                    'status' => false,
                    'message' => 'Driver does not belong to assigned service provider'
                ]);
            }
        }

        // Verify authorization for customer notification
        $user = $this->getBookingUser($bookingModel);
        $isAuthorized = $bookingModel->source == 0 && $request->user_id == $bookingModel->customer_id
            || $bookingModel->source == 1 && $request->external_phone == $bookingModel->external_phone;

        DB::table("booking")->where('booking_id', $request->booking_id)->update([
            'accepted' => $request->driver_id,
            'status' => '1'
        ]);

        if ($driver && $driver->is_radius == 1) {
            $driver->is_radius = 0;
            $driver->update();
        }

        $booking = Booking::where('booking_id', $request->booking_id)->first();
        $driverUser = User::where('id', $booking->accepted)->first();
        $rider_name = $driverUser?->name ?: "Rider";

        $message = "Booking Accepted Successfully";
        if ($user && isset($user->device_token)) {
            $content = "$rider_name is on the way! Grab your headphones and get ready for a safe ride. Please meet them within 3 minutes to avoid any additional wait time charges.";
            $title = 'Booking Accepted!!';
            $this->notification($user, $booking, $title, $content);

            $title2 = "Facing Issues with the Rider?";
            $content2 = "Click on  Emergency Call to connect with our service provider..";
            $this->notification($user, $booking, $title2, $content2);
        }

        return new JsonResponse([
            'status' => true,
            'message' => $message
        ]);
    }

    public function bookingprovideraccept(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:booking,booking_id',
            'subscriber_id' => 'required|integer|exists:subscriber,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $booking = Booking::where('booking_id', $request->booking_id)->first();
        if (!$booking) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Booking not found'
            ]);
        }

        if ((int) $booking->source !== 1) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Provider acceptance allowed only for external bookings'
            ]);
        }

        if ((int) $booking->status !== 0 || !empty($booking->accepted)) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Booking already accepted by driver or not in pending state'
            ]);
        }

        if (!empty($booking->provider_accepted_at)) {
            return new JsonResponse([
                'status' => true,
                'message' => 'Booking already accepted by provider',
            ]);
        }

        if (empty($booking->assigned_subscriber_id) || (int) $booking->assigned_subscriber_id !== (int) $request->subscriber_id) {
            return new JsonResponse([
                'status' => false,
                'message' => 'This booking is not assigned to the given service provider'
            ]);
        }

        Booking::where('booking_id', $request->booking_id)->update([
            'provider_accepted_by' => $request->subscriber_id,
            'provider_accepted_at' => now(),
        ]);

        return new JsonResponse([
            'status' => true,
            'message' => 'Booking accepted by service provider. Assign a driver next.'
        ]);
    }

    public function booking_provider_pending_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subscriber_id' => 'required|integer|exists:subscriber,id',
            'category' => 'nullable|integer',
            'pincode' => 'nullable|digits:6',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $bookings = Booking::where('source', 1)
            ->where('assigned_subscriber_id', (int) $request->subscriber_id)
            ->where('status', 0)
            ->whereNull('accepted')
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category', (int) $request->category);
            })
            ->when($request->filled('pincode'), function ($query) use ($request) {
                $query->where('pincode', $request->pincode);
            })
            ->orderByDesc('id')
            ->get([
                'booking_id',
                'category',
                'distance',
                'duration',
                'pincode',
                'external_name',
                'external_phone',
                'provider_accepted_by',
                'provider_accepted_at',
                'status',
                'created_at',
            ]);

        return new JsonResponse([
            'status' => true,
            'message' => 'Provider pending bookings list',
            'data' => $bookings
        ]);
    }

    public function booking_provider_assign_driver(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:booking,booking_id',
            'subscriber_id' => 'required|integer|exists:subscriber,id',
            'driver_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $booking = Booking::where('booking_id', $request->booking_id)->first();
        if (!$booking) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Booking not found'
            ]);
        }

        if ((int) $booking->source !== 1) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Driver assignment via provider is allowed only for external bookings'
            ]);
        }

        if (empty($booking->assigned_subscriber_id) || (int) $booking->assigned_subscriber_id !== (int) $request->subscriber_id) {
            return new JsonResponse([
                'status' => false,
                'message' => 'This booking is not assigned to the given service provider'
            ]);
        }

        if (empty($booking->provider_accepted_at)) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Provider must accept booking before driver assignment'
            ]);
        }

        if ((int) $booking->status !== 0 || !empty($booking->accepted)) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Booking already accepted by driver or not in pending state'
            ]);
        }

        // Accept either driver.userid (preferred) or driver.id as input.
        $driver = Driver::where('userid', $request->driver_id)
            ->orWhere('id', $request->driver_id)
            ->first();
        if (!$driver) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Driver not found'
            ]);
        }

        if ((int) $driver->subscriberId !== (int) $request->subscriber_id) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Driver does not belong to the given service provider'
            ]);
        }

        Booking::where('booking_id', $request->booking_id)->update([
            // Booking.accepted stores driver userid in this codebase.
            'accepted' => $driver->userid,
            'status' => 1,
        ]);

        return new JsonResponse([
            'status' => true,
            'message' => 'Driver assigned successfully by provider'
        ]);
    }

    public function notification001($user, $booking, $title, $content)
    {
        //return $user;
        if (isset($user) && $user->device_token != "") {
            // return $user;
            $url = 'https://fcm.googleapis.com/fcm/send';

            // Put your Server Key here
            $apiKey = "AAAAxWtUN5g:APA91bEJ-RmWNMZETeGzkgp86XMXlJMnKYrF7TO4Qj8likV3Bs_T8P9QJpev4v1VGRPd5-63E8wCBAHUSSk80bHSEOfHwCKbL71FvxFPgHJ5z1TzVxUOi1xOZqHrXEE-vxsp8wXmrFD_";
            // $apiKey="AAAA7r-3Egc:APA91bE4i_fLddPNSBHNw298mrXNrrHDiSS3WqsIEZKSxPEm9MWv-UmB4YhfrccTsMM_XsVEWJCs0d2JIGVw52ivUnRhlTJMV0Yyb4msIeP5Yt1sycK4FRaL_1e6K9dEOyDGTGOv01N4";
            // $apiKey = "AAAA7r-3Egc:APA91bE4i_fLddPNSBHNw298mrXNrrHDiSS3WqsIEZKSxPEm9MWv-UmB4YhfrccTsMM_XsVEWJCs0d2JIGVw52ivUnRhlTJMV0Yyb4msIeP5Yt1sycK4FRaL_1e6K9dEOyDGTGOv01N4";

            // Compile headers in one variable
            $headers = array(
                'Authorization:key=' . $apiKey,
                'Content-Type:application/json'
            );

            // Add notification content to a variable for easy reference
            $notifData = [
                'title' => "Do Nk ey",
                'body' =>  $title,
                // 'body' => "hai",
                //  "image": "url-to-image",//Optional
                'click_action' => "activities.NotifHandlerActivity" //Action/Activity - Optional
            ];

            $dataPayload = [
                'to' => 'My Name',
                'points' => 80,
                'other_data' => array(["data" => $content])
            ];

            // Create the api body
            $apiBody = [
                'notification' => $notifData,
                'data' => $dataPayload, //Optional
                // 'data' => array(["data" => $notification_data]),
                'time_to_live' => 600, // optional - In Seconds
                //'to' => '/topics/mytargettopic'
                //'registration_ids' = ID ARRAY
                'to' => $user->device_token,
            ];

            // Initialize curl with the prepared headers and body
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));

            // Execute call and save result
            $result = curl_exec($ch);
            // print($result);
            // Close curl after call
            curl_close($ch);
            return 2;
        }
        // return $d11[0]->device_token;
        return 1;
    }

    public function notification($user, $booking, $title, $content)
    {
        if (isset($user) && $user->device_token != "") {

            $projectId = config('services.firebase.user_project_id', 'donkey-user');
            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";
            // Authorization token (replace with your actual bearer token)
            $userToken = site::where('id', 1)?->first()?->userToken;
            $token = $userToken;

            // Create HTTP client instance
            $client = new Client();

            // Headers for the request
            $headers = [
                'Authorization' => $token,
                'Content-Type' => 'application/json',
            ];

            $notifData = [
                'title' => $title,
                'body' => $content,
            ];

            // API body payload with notification and platform-specific settings
            $apiBody = [
                'message' => [
                    'token' => $user->device_token, // Target device token
                    'notification' => $notifData,  // Notification section
                    'android' => [
                        'priority' => 'high',
                        'notification' => [
                            'sound' => 'default',
                            'channel_id' => '1101'
                        ]
                    ],
                    'apns' => [
                        'headers' => [
                            'apns-priority' => '10', // iOS notification priority
                        ],
                    ]
                ]
            ];

            try {
                // Make a POST request to FCM API.
                $response = $client->post($url, [
                    'headers' => $headers,
                    'json' => $apiBody,
                ]);
            } catch (\GuzzleHttp\Exception\RequestException $exception) {
                $statusCode = $exception->hasResponse()
                    ? $exception->getResponse()->getStatusCode()
                    : null;
                $responseBody = $exception->hasResponse()
                    ? (string) $exception->getResponse()->getBody()
                    : $exception->getMessage();

                Log::error('FCM user notification failed', [
                    'project_id' => $projectId,
                    'status_code' => $statusCode,
                    'response' => $responseBody,
                    'booking_id' => $booking?->booking_id,
                ]);

                return false;
            } catch (\Throwable $exception) {
                Log::error('FCM user notification failed unexpectedly', [
                    'project_id' => $projectId,
                    'message' => $exception->getMessage(),
                    'booking_id' => $booking?->booking_id,
                ]);

                return false;
            }

            // Check response status code
            if ($response->getStatusCode() === 200) {
                // Notification sent successfully
                Log::info('Notification sent successfully');
                return true;
            } else {
                // Log unsuccessful responses
                Log::error('Failed to send notification. Status code: ' . $response->getStatusCode());
                return false;
            }
        }

        return 1; // No device token
    }

    public function acceptNotification($user, $bookingId)
    {
        $content = "Hello $user->name, Your Rider is on the way, BookingId is:$bookingId";
        if (isset($user) && isset($user->device_token)) {
            $url = 'https://fcm.googleapis.com/fcm/send';
            $apiKey = "AAAAkWn7nBA:APA91bFram8BPeBiAofotOjsSYKXfnZjW3hFgjKdZjueKj_MHPzOOWHZ3CkT6y-2tu489P2Q0X5EBVKJz4Rpy78O3m-zKP_QsEvUQBV0N2HF6v_m7loQx0zo3yvuWSg8CTcWwvDO9RPC";

            // Compile headers in one variable
            $headers = array(
                'Authorization:key=' . $apiKey,
                'Content-Type:application/json'
            );

            // Add notification content to a variable for easy reference
            $notifData = [
                'title' => "Do N key",
                'body' =>  "Rider is on the way.",
                //  "image": "url-to-image",//Optional
                'click_action' => "activities.NotifHandlerActivity" //Action/Activity - Optional
            ];

            $dataPayload = [
                'to' => 'My Name',
                'points' => 80,
                'other_data' => array(["data" => $content])
            ];

            // Create the api body
            $apiBody = [
                'notification' => $notifData,
                'data' => $dataPayload, //Optional
                // 'data' => array(["data" => $notification_data]),
                'time_to_live' => 600, // optional - In Seconds
                //'to' => '/topics/mytargettopic'
                //'registration_ids' = ID ARRAY
                'to' => $user->device_token,
            ];

            // Initialize curl with the prepared headers and body
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));

            // Execute call and save result
            $result = curl_exec($ch);
            // print($result);
            // Close curl after call
            curl_close($ch);
        }
        // return $d11[0]->device_token;
        return 1;
    }

    public function bookingacceptbackup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required',
            'driver_id' => 'required',
            "user_id" => "required",
            //"payment_method" => "required",
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $booking = DB::table("booking")->where('booking_id', $request->input('booking_id'))->first();

            if (isset($booking)) {
                if ((!isset($booking->accepted)) && ($booking->status == 0)) {

                    // Convert to model instance
                    $booking = DB::table("booking")->where('booking_id', $request->input('booking_id'))->update(['accepted' => $request->input('driver_id'), 'status' => '1']);

                    $driver = Driver::where('userid', $request->input('driver_id'))->first();
                    if (isset($driver) && $driver->is_radius == 1) {
                        $driver->is_radius = 0;
                        $driver->update();
                    }
                    $message = "Booking Accepted Successfully";
                    $user_id = $request->input('user_id');
                    $this->notificationtodriverfirebaseuser($user_id, $request->input('booking_id'), $message);

                    return new JsonResponse([
                        'status' => true,
                        'message' => $message
                    ]);
                } else {
                    $message = "The booking has already been accepted by another driver.";
                    $user_id = $request->input('user_id');
                    $this->notificationtodriverfirebaseuser($user_id, $request->input('booking_id'), $message);

                    return new JsonResponse([
                        'status' => false,
                        'message' => $message
                    ]);
                }
            } else {
                return new JsonResponse([
                    'status' => false,
                    'message' => "You cannot accept this booking as it was not found."
                ]);
            }
        }
    }
    public function notificationtodriverfirebaseuser($user, $bookingid, $message)
    {
        $driverarray = [];
        $pincode = "624003";
        $notification_data = "hai";
        $Pincode = Pincode::where('pincode', $pincode)->get();
        foreach ($Pincode as $pin) {
            if ($pin->pincode == $pincode) {

                $driver = Driver::get(['pincode', 'id', 'userid']);
                foreach ($driver as $drivers) {
                    $driverpin =  json_decode($drivers->pincode);
                    foreach ($driverpin as $p) {
                        if ($p == $pin->id) {
                            //notification for that driver pincode
                            // $user_id = User::where('id', $drivers->id)->get(['user_id']);
                            // if (count($user_id) > 0)
                            array_push($driverarray, $drivers->userid);
                        }
                    }
                }
            }
        }
        $driverarray = array_unique($driverarray);
        if (count($driverarray) > 0) {
            // return $this->sendResponse(array_unique($driverarray), 'These Are the driver id for the notification');
            foreach ($driverarray as $da) {
                $d11 = User::where('id', $da)->get(['device_token']);
                if (count($d11) > 0 && $d11[0]->device_token != "") {
                    $url = 'https://fcm.googleapis.com/fcm/send';

                    // Put your Server Key here
                    $apiKey = "AAAAkWn7nBA:APA91bFram8BPeBiAofotOjsSYKXfnZjW3hFgjKdZjueKj_MHPzOOWHZ3CkT6y-2tu489P2Q0X5EBVKJz4Rpy78O3m-zKP_QsEvUQBV0N2HF6v_m7loQx0zo3yvuWSg8CTcWwvDO9RPC";
                    // $apiKey="AAAA7r-3Egc:APA91bE4i_fLddPNSBHNw298mrXNrrHDiSS3WqsIEZKSxPEm9MWv-UmB4YhfrccTsMM_XsVEWJCs0d2JIGVw52ivUnRhlTJMV0Yyb4msIeP5Yt1sycK4FRaL_1e6K9dEOyDGTGOv01N4";
                    // $apiKey = "AAAA7r-3Egc:APA91bE4i_fLddPNSBHNw298mrXNrrHDiSS3WqsIEZKSxPEm9MWv-UmB4YhfrccTsMM_XsVEWJCs0d2JIGVw52ivUnRhlTJMV0Yyb4msIeP5Yt1sycK4FRaL_1e6K9dEOyDGTGOv01N4";

                    // Compile headers in one variable
                    $headers = array(
                        'Authorization:key=' . $apiKey,
                        'Content-Type:application/json'
                    );

                    // Add notification content to a variable for easy reference
                    $notifData = [
                        'title' => "DOnkey",
                        'body' =>  "Passenger Needed To Travel to a \n Distance :",
                        // 'body' => "hai",
                        //  "image": "url-to-image",//Optional
                        'click_action' => "activities.NotifHandlerActivity" //Action/Activity - Optional
                    ];

                    $dataPayload = [
                        'to' => 'My Name',
                        'points' => 80,
                        'other_data' => array(["data" => $notification_data])
                    ];

                    // Create the api body
                    $apiBody = [
                        'notification' => $notifData,
                        'data' => $dataPayload, //Optional
                        // 'data' => array(["data" => $notification_data]),
                        'time_to_live' => 600, // optional - In Seconds
                        //'to' => '/topics/mytargettopic'
                        //'registration_ids' = ID ARRAY
                        'to' => $d11[0]->device_token,
                    ];

                    // Initialize curl with the prepared headers and body
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));

                    // Execute call and save result
                    $result = curl_exec($ch);
                    // print($result);
                    // Close curl after call
                    curl_close($ch);
                }
            }
        }

        // return $d11[0]->device_token;
        return 1;
    }
    // public function bookingdelete(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'booking_id' => 'required',
    //         'driver_id' => 'required'

    //     ]);
    //     // $p=Hash::make($request->password);
    //     if ($validator->fails()) {
    //         return $this->sendError('Validation Error.', $validator->errors());
    //     } else{
    //         $ignored=DB::table("booking")->where('booking_id', $request->input('booking_id'))->get(['ignored']);
    //         $ignoreddata=explode(",",$ignored[0]->ignored);
    //         $countignored=count($ignoreddata);
    //         $ignoreddata['countignored']=$request->input('driver_id');
    //         $ignoreddataresult= implode(",",$ignoreddata);
    //         DB::table("booking")->where('booking_id', $request->input('booking_id'))->update(['ignored'=>$ignoreddataresult]);
    //         $data="Booking Deleted";
    //         return $this->sendResponse($data, 'Booking Deleted');
    //     }
    // }
    public function bookingignore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required',
            'driver_id' => 'required'

        ]);
        // $p=Hash::make($request->password);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $ignored = DB::table("booking")->where('booking_id', $request->input('booking_id'))->get(['ignored']);
            $ignoreddata = explode(",", $ignored[0]->ignored);
            $countignored = count($ignoreddata);
            $ignoreddata[$countignored] = $request->input('driver_id') . ",";
            // return $ignoreddata;
            $ignoreddataresult = implode(",", $ignoreddata);
            DB::table("booking")->where('booking_id', $request->input('booking_id'))->update(['ignored' => $ignoreddataresult]);
            $data = "Booking Ignored";
            return $this->sendResponse($data, 'Booking Ignored');
        }
    }

    public function bookingcancel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required',
            'driver_id' => 'required',
            'reason' => 'required'

        ]);
        // $p=Hash::make($request->password);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            // $ignored=DB::table("booking")->where('booking_id', $request->input('booking_id'))->get(['ignored']);
            // $ignoreddata=explode(",",$ignored[0]->ignored);
            // $countignored=count($ignoreddata);
            // $ignoreddata[$countignored]=$request->input('driver_id').",";
            // // return $ignoreddata;
            // $ignoreddataresult= implode(",",$ignoreddata);
            DB::table("booking")->where('booking_id', $request->input('booking_id'))->update(['status' => 3, 'reason' => $request->get('reason'), 'cancelledby' => 1]);
            $data = "Booking Canceled By Driver";
            $booking = Booking::where('booking_id', $request->input('booking_id'))->first();
            $user = User::where('user_id', $booking->customer_id)->first();
            if (isset($user->device_token)) {
                $content = "The rider is unable to continue. Please try again or call the service provider for assistance. Don’t forget to rate the service provider’s response rate on our portal.";
                $title = "Rider Canceled the Ride";
                $this->notification($user, $booking, $title, $content);
            }
            return $this->sendResponse($data, 'Booking Canceled By Driver');
        }
    }

    public function bookingcancelbackup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required',
            'driver_id' => 'required',
            'reason' => 'required'

        ]);
        // $p=Hash::make($request->password);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            // $ignored=DB::table("booking")->where('booking_id', $request->input('booking_id'))->get(['ignored']);
            // $ignoreddata=explode(",",$ignored[0]->ignored);
            // $countignored=count($ignoreddata);
            // $ignoreddata[$countignored]=$request->input('driver_id').",";
            // // return $ignoreddata;
            // $ignoreddataresult= implode(",",$ignoreddata);
            DB::table("booking")->where('booking_id', $request->input('booking_id'))->update(['status' => 3, 'reason' => $request->get('reason'), 'cancelledby' => 1]);
            $data = "Booking Canceled By Driver";
            return $this->sendResponse($data, 'Booking Canceled By Driver');
        }
    }


    public function userbookingcancel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required',
            // 'user_id' => 'required'
            'reason' => 'required'

        ]);
        // $p=Hash::make($request->password);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            // $ignored=DB::table("booking")->where('booking_id', $request->input('booking_id'))->get(['ignored']);
            // $ignoreddata=explode(",",$ignored[0]->ignored);
            // $countignored=count($ignoreddata);
            // $ignoreddata[$countignored]=$request->input('user_id').",";
            // // return $ignoreddata;
            // $ignoreddataresult= implode(",",$ignoreddata);
            DB::table("booking")->where('booking_id', $request->input('booking_id'))->update(['status' => 3, 'reason' => $request->get('reason'), 'cancelledby' => 0]);
            $data = "Booking Canceled  By User";
            $title = "Booking Canceled";
            $content = "Booking has been canceled by the user.";
            $booking = Booking::where('booking_id', $request->input('booking_id'))
                ->whereNotNull('accepted')
                ->first();
            if (isset($booking)) {
                $user = User::where('id', $booking->accepted)
                    ->first();
                $result = $this->driverCancelNotification($user, $booking, $title, $content);
            }
            return $this->sendResponse($data, 'Booking Canceled  By User');
        }
    }

    public function driverCancelNotification($user, $booking, $title, $content)
    {
        if (isset($user) && $user->device_token != "") {
            $url = 'https://fcm.googleapis.com/v1/projects/donkey-driver/messages:send';
            // Authorization token (replace with your actual bearer token)
            $driverToken = site::where('id', 1)?->first()?->driverToken;
            $token = $driverToken;

            // Create HTTP client instance
            $client = new Client();

            // Headers for the request
            $headers = [
                'Authorization' => $token,
                'Content-Type' => 'application/json',
            ];

            $notifData = [
                'title' => $title,
                'body' => $content,
            ];

            // API body payload with notification and platform-specific settings
            $apiBody = [
                'message' => [
                    'token' => $user->device_token, // Target device token
                    'notification' => $notifData,  // Notification section
                    'android' => [
                        'priority' => 'high',
                        'notification' => [
                            'sound' => 'default',
                            'channel_id' => '1101'
                        ]
                    ],
                    'apns' => [
                        'headers' => [
                            'apns-priority' => '10', // iOS notification priority
                        ],
                    ]
                ]
            ];

            // Make a POST request to FCM API
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $apiBody,
            ]);

            // Check response status code
            if ($response->getStatusCode() === 200) {
                // Notification sent successfully
                Log::info('Notification sent successfully');
                return true;
            } else {
                // Log unsuccessful responses
                Log::error('Failed to send notification. Status code: ' . $response->getStatusCode());
                return false;
            }
        }

        return 1; // No device token
    }

    public function userbookingcancelwithoutreason(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required',
            // 'user_id' => 'required'
            //            'reason'=>'required'

        ]);
        // $p=Hash::make($request->password);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            // $ignored=DB::table("booking")->where('booking_id', $request->input('booking_id'))->get(['ignored']);
            // $ignoreddata=explode(",",$ignored[0]->ignored);
            // $countignored=count($ignoreddata);
            // $ignoreddata[$countignored]=$request->input('user_id').",";
            // // return $ignoreddata;
            // $ignoreddataresult= implode(",",$ignoreddata);
            DB::table("booking")->where('booking_id', $request->input('booking_id'))->update(['status' => 3, 'cancelledby' => 0]);
            $data = "Booking Canceled  By User";
            return $this->sendResponse($data, 'Booking Canceled  By User');
        }
    }

    public function livetrackingdriver(Request $d)
    {
        $validator = Validator::make($d->all(), [
            'booking_id' => 'required',
            "latitude" => "required",
            "longitude" => "required",
            "speed" => "required"

        ]);
        // $p=Hash::make($request->password);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $latitude = $d->input('latitude');
            $longitude = $d->input('longitude');
            DB::table("booking")->where('booking_id', $d->input('booking_id'))->update(['user_lat' => $latitude, 'user_long' => $longitude, "speed" => $d->input('speed')]);
            // $data['user_latitude'] = \Location::get()->latitude;
            $data['user_latitude'] = $d->input('latitude');
            // $data['user_longitude'] = \Location::get()->longitude;
            $data['user_longitude'] = $d->input('longitude');
            $driver_latlong = DB::table("booking")->where('booking_id', $d->input('booking_id'))->get(['driver_lat', 'driver_long']);
            $data['driver_latitude'] = $driver_latlong[0]->driver_lat;
            $data['driver_longitude'] = $driver_latlong[0]->driver_long;
            return $this->sendResponse($data, 'Track driver location and gives to user');
        }
    }

    public function livetrackinguser(Request $d)
    {
        $validator = Validator::make($d->all(), [
            'booking_id' => 'required',
            "latitude" => "required",
            "longitude" => "required",
            "speed" => "required"

        ]);
        // $p=Hash::make($request->password);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $latitude = $d->input('latitude');
            $longitude = $d->input('longitude');
            DB::table("booking")->where('booking_id', $d->input('booking_id'))->update(['driver_lat' => $latitude, 'driver_long' => $longitude, 'speed' => $d->input('speed')]);
            // $data['driver_latitude'] = \Location::get()->latitude;
            // $data['driver_longitude'] = \Location::get()->longitude;
            $data['driver_latitude'] = $d->input('latitude');
            $data['driver_longitude'] = $d->input('longitude');
            $driver_latlong = DB::table("booking")->where('booking_id', $d->input('booking_id'))->get(['user_lat', 'user_long']);
            $data['user_latitude'] = $driver_latlong[0]->user_lat;
            $data['user_longitude'] = $driver_latlong[0]->user_long;
            return $this->sendResponse($data, 'Track user location and gives to driver');
        }
    }
    // public function bookingdetailsoftheuser(Request $d){
    //      $validator = Validator::make($d->all(), [
    //         'user_id' => 'required',


    //     ]);
    //     // $p=Hash::make($request->password);
    //     if ($validator->fails()) {
    //         return $this->sendError('Validation Error.', $validator->errors());
    //     } else{
    //         $user=User::where('id',$d->input("user_id"))->get(['user_id']);
    //         $booking=DB::table("booking")->where('customer_id',$user[0]->user_id)->latest()->first();
    //         if($booking!=""){
    //          $booking_id= $booking->booking_id;

    //         $book1 = DB::table("booking")->where('booking_id', $booking_id)->get(['customer_id', 'category', 'distance', 'pincode', "booking_id"]);
    //         $user = User::where("user_id", $book1[0]->customer_id)->get(['name', 'email', 'phone', 'user_id']);
    //         $book2 = DB::table("booking_location_mapping")->where('booking_id', $booking_id)->get();
    //         $starting_location = DB::table("booking_locations")->where('location_id', $book2[0]->start_location_id)->get(['address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'landmark']);
    //         $endng_location = DB::table("booking_locations")->where('location_id', $book2[0]->end_location_id)->get(['address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'landmark']);
    //         $book3 = DB::table("booking_payment")->where('booking_id', $booking_id)->get(['total', 'base_price', 'tax', 'tax_split_1', 'tax_split_2']);

    //         $driverdetails = DB::table("booking")->where('booking_id', $booking_id)->get();
    //         // return $driverdetails;
    //         $driver=Driver::where('userid',$driverdetails[0]->accepted)->get(['name','vehicleNo']);
    //         $status=rand(3,5);
    //         $experience=rand(3,20);
    //         array_push($driver,$status);
    //         array_push($driver,$experience);
    //         $book = array($user[0], $book1[0], array("starting_location" => $starting_location[0]), array("ending_location" => $endng_location[0]),array("price" => $book3[0]),array('driver'=>$driver) );
    //         return $this->sendResponse($book, "Booking Details");
    //         }else{
    //             $book=[];
    //         return $this->sendResponse($book, "Booking Details");
    //         }
    //     }
    // }

    // public function getbookingdetailsofid(Request $d)
    // {
    //     $validate = Validator::make($d->all(), [
    //         "booking_id" => "required",
    //     ]);
    //     if ($validate->fails()) {
    //         return $this->sendError($validate->errors());
    //     } else {
    //         $booking_id = $d->get('booking_id');

    //         $book1 = DB::table("booking")->where('booking_id', $booking_id)->get(['customer_id', 'category', 'distance', 'status', 'pincode', "booking_id", "title", "content"]);
    //         if ($book1[0]->status == 0) {
    //             $bookstatus = "Searching For The Driver";
    //         }
    //         if ($book1[0]->status == 1) {
    //             $bookstatus = "Driver Has Accepted the Order And He is On The Way";
    //         }
    //         if ($book1[0]->status == 2) {
    //             $bookstatus = "Booking Has Been Completed";
    //         }
    //         if ($book1[0]->status == 3) {
    //             $bookstatus = "Booking has Been Cancelled";
    //         }
    //         if ($book1[0]->status == 4) {
    //             $bookstatus = "Your Ride Has Started";
    //         }
    //         $book1[0]->booking_status = $bookstatus;
    //         $user = User::where("user_id", $book1[0]->customer_id)->get(['name', 'email', 'phone', 'user_id', 'image']);
    //         $book2 = DB::table("booking_location_mapping")->where('booking_id', $booking_id)->get();
    //         $starting_location = DB::table("booking_locations")->where('location_id', $book2[0]->start_location_id)->get(['address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'landmark']);
    //         $endng_location = DB::table("booking_locations")->where('location_id', $book2[0]->end_location_id)->get(['address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'landmark']);
    //         $book3 = DB::table("booking_payment")->where('booking_id', $booking_id)->get(['total', 'base_price', 'tax', 'tax_split_1', 'tax_split_2', 'coupon_id', 'coupon_amount']);
    //         $book3[0]->roundofftotal = round($book3[0]->total);
    //         if ($book3[0]->coupon_id != null) {
    //             $book3[0]->total = $book3[0]->total - $book3[0]->coupon_amount;
    //         }
    //         // $user[0], $book1[0], array("starting_location" => $starting_location[0]), array("ending_location" => $endng_location[0]),array("price" => $book3[0])
    //         $book = array_merge(array("user" => $user[0]), array("bookingdetails" => $book1[0]), array("starting_location" => $starting_location[0]), array("ending_location" => $endng_location[0]), array("price" => $book3[0]), array("distance" => round($book1[0]->distance)));
    //         // return array_merge(array("starting_location" => $starting_location[0]), array("ending_location" => $endng_location[0]));
    //         // $book = array($user[0], $book1[0], array("starting_location" => $starting_location[0]), array("ending_location" => $endng_location[0]),array("price" => $book3[0]) );
    //         // $book = array_merge($book1, $starting_location,  $endng_location, $book3);
    //         // $book = array_push($book1, $book3);
    //         // $book = DB::table('booking')->join('booking_location_mapping', "booking.booking_id", "=", "booking_location_mapping.booking_id")->join('booking_locations', "booking_locations.location_id", "=", "booking_location_mapping.start_location_id")->where("booking.booking_id", $booking_id)->get();
    //         return $this->sendResponse($book, "Booking Details");
    //     }
    // }

    public function getbookingdetailsofid(Request $d)
    {
        $validate = Validator::make($d->all(), [
            "booking_id" => "required",
        ]);

        if ($validate->fails()) {
            return $this->sendError($validate->errors());
        }

        $booking_id = $d->get('booking_id');

        // ✅ Booking
        $book1 = DB::table("booking")
            ->where('booking_id', $booking_id)
            ->first(['customer_id', 'category', 'distance', 'status', 'pincode', "booking_id", "title", "content"]);

        if (!$book1) {
            return $this->sendError("Booking not found");
        }

        // ✅ Status
        switch ($book1->status) {
            case 0:
                $bookstatus = "Searching For The Driver";
                break;
            case 1:
                $bookstatus = "Driver Has Accepted the Order And He is On The Way";
                break;
            case 2:
                $bookstatus = "Booking Has Been Completed";
                break;
            case 3:
                $bookstatus = "Booking has Been Cancelled";
                break;
            case 4:
                $bookstatus = "Your Ride Has Started";
                break;
            default:
                $bookstatus = "Unknown";
        }

        $book1->booking_status = $bookstatus;

        // ✅ User
        $user = User::where("user_id", $book1->customer_id)
            ->first(['name', 'email', 'phone', 'user_id', 'image']);

        // ✅ Location Mapping
        $book2 = DB::table("booking_location_mapping")
            ->where('booking_id', $booking_id)
            ->first();

        if (!$book2) {
            return $this->sendError("Location mapping not found");
        }

        // ✅ Starting Location
        $starting_location = DB::table("booking_locations")
            ->where('location_id', $book2->start_location_id)
            ->first(['address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'landmark']);

        // ✅ Ending Location
        $ending_location = DB::table("booking_locations")
            ->where('location_id', $book2->end_location_id)
            ->first(['address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'landmark']);

        // ✅ Price
        $book3 = DB::table("booking_payment")
            ->where('booking_id', $booking_id)
            ->first(['total', 'base_price', 'tax', 'tax_split_1', 'tax_split_2', 'coupon_id', 'coupon_amount']);

        if ($book3) {
            $book3->roundofftotal = round($book3->total);

            if ($book3->coupon_id != null) {
                $book3->total = $book3->total - $book3->coupon_amount;
            }
        }

        // ✅ Final Response
        $book = [
            "user" => $user,
            "bookingdetails" => $book1,
            "starting_location" => $starting_location,
            "ending_location" => $ending_location,
            "price" => $book3,
            "distance" => round($book1->distance)
        ];

        return $this->sendResponse($book, "Booking Details");
    }

    public function getbookingdetails(Request $request)
    {
        $validated = $request->validate([
            "driver_user_id" => "required",
            "radius" => "required"
        ]);

        $driverUserId = $request->input('driver_user_id');

        $driver = Driver::where('userid', $driverUserId)->first();
        //return $driver;
        if (!$driver) {
            return $this->sendError("Driver not found");
        }

        if ($driver->is_radius == 1) {
            //return "location";
            // If the driver has is_radius equal to 1, use getBookingViaLocation method
            return $this->getBookingViaLocation($request);
        }
        //return "pincode";
        // If the driver does not have is_radius equal to 1, use getBookingDetails method
        return $this->getBookingDetailsByPincode($request);
    }

    public function getBookingDetailsByPincode(Request $d)
    {
        //return "pincode";
        $validate = Validator::make($d->all(), [
            "driver_user_id" => "required",
            "radius" => "nullable"
        ]);
        if ($validate->fails()) {
            return $this->sendError($validate->errors());
        } else {
            $driver_id = $d->get('driver_user_id');

            $Driver = DB::table("driver")->where('userid', $driver_id)?->first();
            //return $Driver;
            $category = [];
            if ($Driver->type == 2) {
                $category[] = 4;
            } elseif ($Driver->type == 3) {
                $category[] = 5;
            } else {
                $category = [1, 2, 3];
            }
            // return $category;
            if (!$Driver) {
                return $this->sendError("Driver not found");
            }

            $subscriber = DB::table("subscriber")->where('id', $Driver?->subscriberId)?->first();
            if (($subscriber?->activestatus == 1) && ($subscriber?->blockedstatus == 1)) {
                // $user = User::where('user_id', $driver_id)->get();
                // $driver = Driver::where('userid', $driver_id)->get(['pincode']);
                $driver = Driver::leftjoin('users', 'users.id', '=', 'driver.userid')->where(['driver.userid' => $driver_id, 'users.is_live' => 1])->get(['pincode']);
                //return $driver[0]->pincode;
                if (count($driver) > 0) {
                    // if (is_array(json_decode($driver[0]->pincode))) {
                    //     return "true";
                    // } else {
                    //     return "false";
                    // }
                    foreach (json_decode($driver[0]->pincode) as $pin) {
                        $pincodes[] = Pincode::where('id', $pin)->get(['pincode']);
                    }
                    $book1 = [];
                    //return $pincodes;
                    foreach ($pincodes as $key => $value) {
                        // $iii=DB::table("booking")->where('status', 0)->whereIn('category',$category)->where('pincode', $value->pincode)->get('ignored');
                        // $counti=0;
                        // foreach($iii as $i1){
                        //     $ignoredd=explode(',',$i1->ignored);
                        //     for($iiii=0;$iiii<count($ignoredd);$iiii++){
                        //         if($ignoredd[$iiii]==$driver_id){
                        //             $counti=1;
                        //         }
                        //     }
                        // }
                        // if($counti==0){}
                        $result = DB::table("booking")->where('status', 0)->whereIn('category', $category)->where('pincode', $value[0]['pincode'])->where('accepted', NULL)->get(['customer_id', 'category', 'distance', 'pincode', 'booking_id', 'status', 'ignored']);
                        // Only push non-empty results
                        if (!$result->isEmpty()) {
                            array_push($book1, $result);
                        }
                    }
                    //return $book1;
                    $book11 = [];
                    //$bookings = $book1[0];
                    if (!empty($book1) && isset($book1[0])) {
                        $bookings = $book1[0];
                    } else {
                        $bookings = []; // or handle accordingly, e.g., set to null or return a default value
                    }
                    foreach ($bookings as $key => $booking) {
                        $explodedIgnored = explode(',', $booking->ignored);
                        if (!in_array($d->driver_user_id, $explodedIgnored)) {
                            $book11[] = $booking;
                        }
                    }
                    $book1 = $book11;
                    //return $book11;
                    $book = [];
                    for ($i = 0; $i < count($book1); $i++) {
                        // for ($i = 0; $i < 2; $i++) {
                        $user = User::where("user_id", $book1[$i]->customer_id)->get(['name', 'email', 'phone', 'user_id', 'image']);
                        $book2 = DB::table("booking_location_mapping")->where('booking_id', $book1[$i]->booking_id)->get();
                        //return $book2;
                        $book3 = DB::table("booking_payment")->where('booking_id', $book1[$i]->booking_id)->get(['total', 'base_price', 'tax', 'tax_split_1', 'tax_split_2', 'coupon_id', 'coupon_amount']);
                        foreach ($book2 as $b) {
                            $starting_location = DB::table("booking_locations")->where('location_id', $b?->start_location_id)->get(['address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'landmark', 'lat as starting_latitude', 'long as starting_longitude']);
                            $endng_location = DB::table("booking_locations")->where('location_id', $b?->end_location_id)->get(['address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'landmark', 'lat as ending_latitude', 'long as ending_longitude']);
                        }
                        if ($book1[$i]->status == 0) {
                            $bookstatus = "Searching For The Driver";
                        }
                        if ($book1[$i]->status == 1) {
                            $bookstatus = "Driver Has Accepted the Order And He is On The Way";
                        }
                        if ($book1[$i]->status == 2) {
                            $bookstatus = "Booking Has Been Completed";
                        }
                        if ($book1[$i]->status == 3) {
                            $bookstatus = "Booking has Been Canceled";
                        }
                        if ($book1[$i]->status == 4) {
                            $bookstatus = "Your Ride Has Started";
                        }
                        $book1[$i]->booking_status = $bookstatus;
                        foreach ($book3 as $b) {
                            $pay = [];
                            if ($b?->coupon_id != null) {
                                $pay['total'] = $b->total - $b?->coupon_amount;
                            } else {
                                $pay['total'] = $b->total;
                            }
                            $pay['roundofftotal'] = round($b->total);
                            $pay['base_price'] = $b->base_price;
                            $pay['tax'] = $b->tax;
                            $pay['tax_split_1'] = $b->tax_split_1;
                            $pay['tax_split_2'] = $b->tax_split_2;
                            $pay['coupon_id'] = $b?->coupon_id;
                            $pay['coupon_amount'] = $b?->coupon_amount;
                        }
                        $bookresult = array_merge(array("user" => $user[0]), array("bookingdetails" => $book1[$i]), array("starting_location" => $starting_location[0]), array("ending_location" => $endng_location[0]), array("price" => $pay), array("distance" => round($book1[$i]->distance)));
                        // array_push($book, array($user[0], $book1[$i], array("starting_location" => $starting_location), array("ending_location" => $endng_location),array("price" => $book3) ));
                        array_push($book, $bookresult);
                        // array_push($book, array($book1[$i], $book3));
                    }
                    // $book1=[];
                    // array$book1
                    return $this->sendResponse($book, "Booking Details");
                } else {
                    return $this->sendError("There is No such Driver ");
                }
            } else {
                return $this->sendError("Your Subscriber is currently Inactive or Blocked");
            }
        }
    }

    public function getBookingViaLocation(Request $request)
    {
        // return "location";
        $validated = $request->validate([
            "driver_user_id" => "required",
            "radius" => "nullable"
        ]);

        $driverUserId = $request->input('driver_user_id');

        // Retrieve driver's latitude and longitude from the Driver table
        $driver = Driver::where('userid', $driverUserId)->first();
        $category = [];
        if ($driver->type == 2) {
            $category[] = 4;
        } elseif ($driver->type == 3) {
            $category[] = 5;
        } else {
            $category = [1, 2, 3];
        }

        //return $subscriber;
        if (!$driver) {
            return $this->sendError("Driver not found");
        }

        $subscriber = DB::table("subscriber")->where('id', $driver?->subscriberId)?->first();

        if (($subscriber?->activestatus == 1) && ($subscriber?->blockedstatus == 1)) {
            $driverLatitude = $driver?->lat;
            $driverLongitude = $driver?->long;

            // Ensure radius is provided and convert to kilometers
            $radiusInMeters = $validated['radius'] ?? 1000; // Default radius to 1000 meters if not provided
            $radiusInKilometers = $radiusInMeters / 1000; // Convert radius to kilometers

            // Convert radius from kilometers to degrees
            $radiusInDegrees = $radiusInKilometers / 111.12; // 1 degree ≈ 111.12 kilometers

            // Calculate the bounding box around the driver's location in degrees
            $maxLatDegrees = $driverLatitude + $radiusInDegrees;
            $minLatDegrees = $driverLatitude - $radiusInDegrees;
            $maxLngDegrees = $driverLongitude + ($radiusInDegrees / cos(deg2rad($driverLatitude)));
            $minLngDegrees = $driverLongitude - ($radiusInDegrees / cos(deg2rad($driverLatitude)));

            $bookings = DB::table("booking")
                ->whereIn('category', $category)
                ->where('status', 0) // Filter bookings by status if needed
                ->where('accepted', NULL)
                ->whereBetween('user_lat', [$minLatDegrees, $maxLatDegrees])
                ->whereBetween('user_long', [$minLngDegrees, $maxLngDegrees])
                ->get();

            $book11 = [];
            foreach ($bookings as $key => $booking) {
                $explodedIgnored = explode(',', $booking->ignored);
                if (!in_array($validated['driver_user_id'], $explodedIgnored)) {
                    $book11[] = $booking;
                }
            }
            $bookings = $book11;

            $book = [];
            foreach ($bookings as $booking) {
                // You can process each booking here as needed
                $user = User::where("user_id", $booking->customer_id)->first(['name', 'email', 'phone', 'user_id', 'image']);
                $book2 = DB::table("booking_location_mapping")->where('booking_id', $booking->booking_id)->get();
                $book3 = DB::table("booking_payment")->where('booking_id', $booking->booking_id)->get(['total', 'base_price', 'tax', 'tax_split_1', 'tax_split_2', 'coupon_id', 'coupon_amount']);
                foreach ($book2 as $b) {
                    $starting_location = DB::table("booking_locations")->where('location_id', $b->start_location_id)->first(['address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'landmark', 'lat as starting_latitude', 'long as starting_longitude']);
                    $endng_location = DB::table("booking_locations")->where('location_id', $b->end_location_id)->first(['address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'landmark', 'lat as ending_latitude', 'long as ending_longitude']);
                }
                if ($booking->status == 0) {
                    $bookstatus = "Searching For The Driver";
                } elseif ($booking->status == 1) {
                    $bookstatus = "Driver Has Accepted the Order And He is On The Way";
                } elseif ($booking->status == 2) {
                    $bookstatus = "Booking Has Been Completed";
                } elseif ($booking->status == 3) {
                    $bookstatus = "Booking has Been Cancelled";
                } elseif ($booking->status == 4) {
                    $bookstatus = "Your Ride Has Started";
                }

                foreach ($book3 as $b) {
                    $pay = [];
                    $pay['total'] = $b->total;
                    $pay['roundofftotal'] = round($b->total);
                    $pay['base_price'] = $b->base_price;
                    $pay['tax'] = $b->tax;
                    $pay['tax_split_1'] = $b->tax_split_1;
                    $pay['tax_split_2'] = $b->tax_split_2;
                    $pay['coupon_id'] = $b?->coupon_id;
                    $pay['coupon_amount'] = $b?->coupon_amount;
                }

                $bookingDetails = [
                    "user" => $user,
                    "bookingdetails" => $booking,
                    "starting_location" => $starting_location,
                    "ending_location" => $endng_location,
                    "price" => $pay,
                    "distance" => round($booking->distance),
                    "booking_status" => $bookstatus
                ];

                $book[] = $bookingDetails;
            }

            if (!empty($book)) {
                return $this->sendResponse($book, "Booking Details");
            } else {
                return $this->sendError("No bookings found within the specified radius.");
            }
        } else {
            return $this->sendError("Your Subscriber is currently Inactive or Blocked");
        }
    }

    public function getBookingViaLocationbackup0(Request $request)
    {
        $validated = $request->validate([
            "driver_user_id" => "required",
            "radius" => "required"
        ]);

        //return $validated;
        $driverUserId = $request->input('driver_user_id');

        // Retrieve driver's latitude and longitude from the Driver table
        $driver = Driver::where('userid', $driverUserId)->first();

        if (!$driver) {
            return $this->sendError("Driver not found");
        }

        $driverLatitude = $driver->lat;
        $driverLongitude = $driver->long;

        // Define the radius within which to search for bookings
        $radius = $validated['radius']; // Adjust the radius as needed

        // Calculate the bounding box around the driver's location
        $maxLat = $driverLatitude + ($radius / 111.2); // Approximately 111.2 kilometers per degree of latitude
        $minLat = $driverLatitude - ($radius / 111.2);
        $maxLng = $driverLongitude + ($radius / (111.2 * cos(deg2rad($driverLatitude))));
        $minLng = $driverLongitude - ($radius / (111.2 * cos(deg2rad($driverLatitude))));

        // Retrieve bookings within the bounding box
        $bookings = DB::table("booking")
            ->where('status', 0) // Filter bookings by status if needed
            ->whereBetween('user_lat', [$minLat, $maxLat])
            ->whereBetween('user_long', [$minLng, $maxLng])
            ->get();

        $book = [];
        foreach ($bookings as $booking) {
            // You can process each booking here as needed
            $user = User::where("user_id", $booking->customer_id)->first(['name', 'email', 'phone', 'user_id']);
            $book2 = DB::table("booking_location_mapping")->where('booking_id', $booking->booking_id)->get();
            $book3 = DB::table("booking_payment")->where('booking_id', $booking->booking_id)->get(['total', 'base_price', 'tax', 'tax_split_1', 'tax_split_2']);
            foreach ($book2 as $b) {
                $starting_location = DB::table("booking_locations")->where('location_id', $b->start_location_id)->first(['address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'landmark', 'lat as starting_latitude', 'long as starting_longitude']);
                $endng_location = DB::table("booking_locations")->where('location_id', $b->end_location_id)->first(['address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'landmark', 'lat as ending_latitude', 'long as ending_longitude']);
            }
            if ($booking->status == 0) {
                $bookstatus = "Searching For The Driver";
            } elseif ($booking->status == 1) {
                $bookstatus = "Driver Has Accepted the Order And He is On The Way";
            } elseif ($booking->status == 2) {
                $bookstatus = "Booking Has Been Completed";
            } elseif ($booking->status == 3) {
                $bookstatus = "Booking has Been Cancelled";
            } elseif ($booking->status == 4) {
                $bookstatus = "Your Ride Has Started";
            }

            foreach ($book3 as $b) {
                $pay = [];
                $pay['total'] = $b->total;
                $pay['roundofftotal'] = round($b->total);
                $pay['base_price'] = $b->base_price;
                $pay['tax'] = $b->tax;
                $pay['tax_split_1'] = $b->tax_split_1;
                $pay['tax_split_2'] = $b->tax_split_2;
            }

            $bookingDetails = [
                "user" => $user,
                "bookingdetails" => $booking,
                "starting_location" => $starting_location,
                "ending_location" => $endng_location,
                "price" => $pay,
                "distance" => round($booking->distance),
                "booking_status" => $bookstatus
            ];

            $book[] = $bookingDetails;
        }

        if (!empty($book)) {
            return $this->sendResponse($book, "Booking Details");
        } else {
            return $this->sendError("No bookings found within the specified radius.");
        }
    }

    public function getbookingdetails1(Request $d)
    {
        $validate = Validator::make($d->all(), [
            "driver_user_id" => "required",
        ]);
        if ($validate->fails()) {
            return $this->sendError($validate->errors());
        } else {
            $driver_id = $d->get('driver_user_id');
            // $user = User::where('user_id', $driver_id)->get();
            // $driver = Driver::where('userid', $driver_id)->get(['pincode']);
            $driver = Driver::where('userid', $driver_id)->get(['pincode']);
            if (count($driver) > 0) {
                // if (is_array(json_decode($driver[0]->pincode))) {
                //     return "true";
                // } else {
                //     return "false";
                // }
                foreach (json_decode($driver[0]->pincode) as $pin) {
                    $pincodes = Pincode::where('id', $pin)->get(['pincode']);
                }
                $book1 = [];
                $book11 = [];
                $book12 = [];
                foreach ($pincodes as $key => $value) {
                    // $iii=DB::table("booking")->where('status', 0)->where('pincode', $value->pincode)->get('ignored');
                    // $counti=0;
                    // foreach($iii as $i1){
                    //     $ignoredd=explode(',',$i1->ignored);
                    //     for($iiii=0;$iiii<count($ignoredd);$iiii++){
                    //         if($ignoredd[$iiii]==$driver_id){
                    //             $counti=1;
                    //         }
                    //     }
                    // }
                    // if($counti==0){}
                    $it = "%," . $driver_id . ",%";
                    // return $it;
                    array_push($book11, DB::table("booking")->where('status', 0)->where('pincode', $value->pincode)->where('accepted', NULL)->get(['customer_id', 'category', 'distance', 'pincode', 'booking_id', 'status', 'ignored']));
                    // array_push($book12, DB::table("booking")->where('status', 0)->where('pincode', $value->pincode)->where('accepted',NULL)->where('ignored',"LIKE",$it)->get(['customer_id', 'category', 'distance', 'pincode', 'booking_id' ,'status','ignored']));
                }
                // return $book11;
                if (count($book11[0]) > 0) {
                    for ($i = 0; $i < count($book11[0]); $i++) {
                        $ignore = explode(',', $book11[0][$i]->ignored);
                        $igc = 0;
                        //  return $ignore;
                        foreach ($ignore as $ig) {
                            if ($ig == $driver_id) {
                                $igc = 1;
                            }
                        }
                        if ($igc == 0) {
                            array_push($book1, DB::table("booking")->where('booking_id', $book11[0][$i]->booking_id)->get(['customer_id', 'category', 'distance', 'pincode', 'booking_id', 'status', 'ignored']));
                        }
                    }
                }
                // else{
                //     // array_push($book1, DB::table("booking")->where('status', 0)->where('pincode', $value->pincode)->where('accepted',NULL)->get(['customer_id', 'category', 'distance', 'pincode', 'booking_id' ,'status','ignored']));
                //     $book1=[];
                // }

                // return $r=count($book1);
                // return $book1[0];
                $book = [];
                if (count($book1) > 0) {
                    for ($i = 0; $i < count($book1); $i++) {
                        // for ($i = 0; $i < 2; $i++) {
                        $user = User::where("user_id", $book1[$i][0]->customer_id)->get(['name', 'email', 'phone', 'user_id']);
                        $book2 = DB::table("booking_location_mapping")->where('booking_id', $book1[$i][0]->booking_id)->get();
                        $book3 = DB::table("booking_payment")->where('booking_id', $book1[$i][0]->booking_id)->get(['total', 'base_price', 'tax', 'tax_split_1', 'tax_split_2']);
                        $starting_location = [];
                        $endng_location = [];
                        $pay = [];
                        foreach ($book2 as $b) {
                            $starting_location = DB::table("booking_locations")->where('location_id', $b->start_location_id)->get(['address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'landmark', 'lat as starting_latitude', 'long as starting_longitude']);
                            $endng_location = DB::table("booking_locations")->where('location_id', $b->end_location_id)->get(['address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'landmark', 'lat as ending_latitude', 'long as ending_longitude']);
                        }
                        foreach ($book3 as $b) {
                            $pay = [];
                            $pay['total'] = $b->total;
                            $pay['roundofftotal'] = round($b->total);
                            $pay['base_price'] = $b->base_price;
                            $pay['tax'] = $b->tax;
                            $pay['tax_split_1'] = $b->tax_split_1;
                            $pay['tax_split_2'] = $b->tax_split_2;
                        }
                        if (empty($starting_location) && empty($endng_location)) {
                            $bookresult = array_merge(array("user" => $user[0]), array("bookingdetails" => $book1[$i][0]), array("starting_location" => $starting_location), array("ending_location" => $endng_location), array("price" => $pay), array("distance" => round($book1[$i][0]->distance)));
                        } else {
                            $bookresult = array_merge(array("user" => $user[0]), array("bookingdetails" => $book1[$i][0]), array("starting_location" => $starting_location[0]), array("ending_location" => $endng_location[0]), array("price" => $pay), array("distance" => round($book1[$i][0]->distance)));
                        }

                        // array_push($book, array($user[0], $book1[0][$i], array("starting_location" => $starting_location), array("ending_location" => $endng_location),array("price" => $book3) ));
                        array_push($book, $bookresult);
                        // array_push($book, array($book1[$i], $book3));
                    }
                }
                // $book1=[];
                // array$book1
                return $this->sendResponse($book, "Booking Details");
            } else {
                return $this->sendError("There is No such Driver ");
            }
        }
    }









    //  public function bookingdetailsoftheuser(Request $d){
    //      $validator = Validator::make($d->all(), [
    //         'user_id' => 'required',


    //     ]);
    //     // $p=Hash::make($request->password);
    //     if ($validator->fails()) {
    //         return $this->sendError('Validation Error.', $validator->errors());
    //     } else{
    //         $user=User::where('id',$d->input("user_id"))->get(['user_id']);
    //         $booking=DB::table("booking")->where('customer_id',$user[0]->user_id)->where('status',0)->latest()->first();
    //         if($booking!=""){
    //          $booking_id= $booking->booking_id;

    //         $book1 = DB::table("booking")->where('booking_id', $booking_id)->get(['customer_id', 'category', 'distance', 'pincode', "booking_id"]);
    //         $user = User::where("user_id", $book1[0]->customer_id)->get(['name', 'email', 'phone', 'user_id as uniqueid','id as userid']);
    //         $book2 = DB::table("booking_location_mapping")->where('booking_id', $booking_id)->get();
    //         $starting_location = DB::table("booking_locations")->where('location_id', $book2[0]->start_location_id)->get(['address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'landmark']);
    //         $endng_location = DB::table("booking_locations")->where('location_id', $book2[0]->end_location_id)->get(['address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'landmark']);
    //         $book3 = DB::table("booking_payment")->where('booking_id', $booking_id)->get(['total', 'base_price', 'tax', 'tax_split_1', 'tax_split_2']);

    //         $driverdetails = DB::table("booking")->where('booking_id', $booking_id)->latest()->first();
    //         if($driverdetails->accepted!=""){
    //         $driver=Driver::where('userid',$driverdetails->accepted)->get(['name','vehicleNo','userid as driverid']);

    //         $status=rand(3,5);
    //         $experience=rand(3,20);
    //         $driver[0]->status=$status;
    //         $driver[0]->experience=$experience;
    //         // array_push($driver,$status);
    //         // array_push($driver,$experience);
    //         // $book = array($user[0], $book1[0], array("starting_location" => $starting_location[0]), array("ending_location" => $endng_location[0]),array("price" => $book3[0]),array('driver'=>$driver) );
    //         $book= array_merge(array("user" => $user[0]), array("bookingdetails" => $book1[0]),array("starting_location" => $starting_location[0]), array("ending_location" => $endng_location[0]),array("price" => $book3[0]),array('driver'=>$driver[0]));
    //         return $this->sendResponse($book, "Booking Details");
    //         }
    //         $book= array_merge(array("user" => $user[0]), array("bookingdetails" => $book1[0]),array("starting_location" => $starting_location[0]), array("ending_location" => $endng_location[0]),array("price" => $book3[0]));

    //         return $this->sendResponse($book, "Booking Details but Driver Not accepted");
    //         }else{
    //             $book=[];
    //         return $this->sendResponse($book, "Booking Details");
    //         }
    //     }
    // }
    public function bookingdetailsoftheuser(Request $d)
    {
        // Treat the plain donkey route as internal by default.
        // Only the dedicated /api/external/* route should require external_phone.
        $isExternal = $d->routeIs('external.bookingdetailsoftheuser')
            || str_starts_with((string) $d->path(), 'api/external/');

        // Accept both snake_case and camelCase payloads from different clients.
        if (!$d->filled('user_id') && $d->filled('userId')) {
            $d->merge(['user_id' => $d->input('userId')]);
        }
        if (!$d->filled('external_phone') && $d->filled('externalPhone')) {
            $d->merge(['external_phone' => $d->input('externalPhone')]);
        }
        if (!$d->filled('external_name') && $d->filled('externalName')) {
            $d->merge(['external_name' => $d->input('externalName')]);
        }

        $d->merge(['source' => $isExternal ? 1 : 0]);

        $validator = Validator::make($d->all(), [
            'source' => 'nullable|in:0,1',
            'user_id' => $isExternal ? 'nullable' : 'required',
            'external_phone' => $isExternal ? 'required' : 'nullable',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $source = $isExternal ? 1 : (int) $d->get('source', 0);
            $booking = null;
            $user = null;
            $external_name = $d->external_name;
            $external_phone = $d->external_phone;

            if ($source === 0) {
                $user = User::where('id', $d->user_id)
                    ->orWhere('user_id', $d->user_id)
                    ->first(['id', 'name', 'email', 'phone', 'user_id']);

                if (!$user) {
                    return $this->sendError("User not found");
                }

                $booking = DB::table("booking")
                    ->where('customer_id', $user->user_id)
                    ->latest()
                    ->first();
            } else {
                $booking = DB::table("booking")
                    ->where('external_phone', $external_phone)
                    ->latest()
                    ->first();
            }

            if ($booking) {
                $booking_id = $booking->booking_id;

                $book1 = DB::table("booking")->where('booking_id', $booking_id)->get(['customer_id', 'category', 'distance', 'duration', 'pincode', "booking_id", 'otp', 'status']);
                if ($book1[0]->status == 0) {
                    $bookstatus = "Searching For The Driver";
                }
                if ($book1[0]->status == 1) {
                    $bookstatus = "Driver Has Accepted the Order And He is On The Way";
                }
                if ($book1[0]->status == 2) {
                    $bookstatus = "Booking Has Been Completed";
                }
                if ($book1[0]->status == 3) {
                    $bookstatus = "Booking has Been Cancelled";
                }
                if ($book1[0]->status == 4) {
                    $bookstatus = "Your Ride Has Started";
                }
                $book1[0]->booking_status = $bookstatus;
                if ($source === 0) {
                    $user = User::where("user_id", $book1[0]->customer_id)
                        ->first(['name', 'email', 'phone', 'user_id as uniqueid', 'id as userid']);
                } else {
                    $user = (object) [
                        "name" => $external_name,
                        "phone" => $external_phone,
                        "email" => null,
                        "uniqueid" => null,
                        "userid" => null
                    ];
                }
                $book2 = DB::table("booking_location_mapping")->where('booking_id', $booking_id)->get();
                $starting_location = DB::table("booking_locations")->where('location_id', $book2[0]->start_location_id)->get(['address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'landmark', 'lat as starting_latitude', 'long as starting_longitude']);
                $endng_location = DB::table("booking_locations")->where('location_id', $book2[0]->end_location_id)->get(['address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'landmark', 'lat as ending_latitude', 'long as ending_longitude']);
                $book3 = DB::table("booking_payment")->where('booking_id', $booking_id)->get(['total', 'base_price', 'tax', 'tax_split_1', 'tax_split_2', 'service_cost', 'coupon_id', 'coupon_amount']);
                if ($book3[0]->coupon_id != null) {
                    $book3[0]->total = $book3[0]->total - $book3[0]->coupon_amount;
                }
                $driverdetails = DB::table("booking")->where('booking_id', $booking_id)->latest()->first();
                if ($driverdetails->accepted != "") {
                    $driver = Driver::leftjoin('users', 'users.id', '=', 'driver.userid')->where('driver.userid', $driverdetails->accepted)->get(['driver.name', 'driver.vehicleNo', 'driver.vehicleModelNo', 'driver.language', 'driver.userid as driverid', 'users.image']);
                    $driverRating = BookingRating::where('driver_id', $driver[0]?->driverid)?->get();
                    if (count($driverRating) > 0) {
                        $driverRatingCount = $driverRating->count();
                        $driverRatingPoints = $driverRating->sum('rating');
                        $averageRating = $driverRatingPoints / $driverRatingCount;
                        $rating = round($averageRating, 1);
                        $driver[0]->rating = $rating;
                    } else {
                        $driver[0]->rating = 0;
                    }

                    // return $driverRating;
                    //$driverTotalduration = DB::table("booking")->where('accepted', $driver[0]->driverid)?->get()->sum('duration');
                    $driverTotalduration = DB::table("booking")->where('accepted', $driver[0]->driverid)?->get()->sum('duration');
                    $driver[0]['driverTotalduration'] = $driverTotalduration;
                    $location = DB::table("booking")->where('booking_id', $booking_id)->get();
                    $timing = $location[0]->distance * 10;
                    //             $earthRadius = 6371000;
                    //             $latFrom = deg2rad($location[0]->user_lat);
                    //   $lonFrom = deg2rad($location[0]->user_long);
                    //   $latTo = deg2rad($location[0]->driver_lat);
                    //   $lonTo = deg2rad($location[0]->driver_long);
                    //   return $lonTo;
                    //   $latDelta = $latTo - $latFrom;
                    //   $lonDelta = $lonTo - $lonFrom;

                    //   $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                    //     cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
                    //   $distance_between= $angle * $earthRadius;
                    //   return $distance_between;

                    $status = rand(3, 5);
                    $experience = rand(3, 20);
                    $driver[0]->status = $status;
                    $driver[0]->experience = $experience;
                    $driver[0]->time_to_reach = $timing . "Min";
                    $driver[0]->distance = round($location[0]->distance);
                    // array_push($driver,$status);
                    // array_push($driver,$experience);
                    // $book = array($user[0], $book1[0], array("starting_location" => $starting_location[0]), array("ending_location" => $endng_location[0]),array("price" => $book3[0]),array('driver'=>$driver) );
                    $book = array_merge(array("user" => $user), array("bookingdetails" => $book1[0]), array("starting_location" => $starting_location[0]), array("ending_location" => $endng_location[0]), array("price" => $book3[0]), array('driver' => $driver[0]));
                    return $this->sendResponse($book, "Booking Details");
                }
                $book = array_merge(array("user" => $user), array("bookingdetails" => $book1[0]), array("starting_location" => $starting_location[0]), array("ending_location" => $endng_location[0]), array("price" => $book3[0]));

                return $this->sendResponse($book, "Booking Details but Driver Not accepted");
            } else {
                $book = [];
                return $this->sendResponse($book, "Booking Details");
            }
        }
    }

    public function driverbookingcomplete(Request $d)
    {
        $validate = Validator::make($d->all(), [
            "booking_id" => "required",
        ]);
        if ($validate->fails()) {
            return $this->sendError($validate->errors());
        } else {
            $booking_id = $d->get('booking_id');
            // $user = User::where('user_id', $driver_id)->get();
            // $driver = Driver::where('userid', $driver_id)->get(['pincode']);
            DB::table("booking")->where('booking_id', $booking_id)->update(['status' => 2]);
            return $this->sendResponse($data['booking_id'] = $booking_id, "Booking Completed");
        }
    }

    public function locationuser(Request $d)
    {
        $validator = Validator::make($d->all(), [
            'booking_id' => 'required',


        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {

            $userdetails = DB::table("booking")->where('booking_id', $d->input('booking_id'))->get(['user_lat', 'user_long', 'speed']);

            $data = $userdetails[0];
            return $this->sendResponse($data, 'User location ');
        }
    }
    public function locationdriver(Request $d)
    {
        $validator = Validator::make($d->all(), [
            'booking_id' => 'required',


        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {

            $userdetails = DB::table("booking")->where('booking_id', $d->input('booking_id'))->get(['driver_lat', 'driver_long', 'speed', 'distance']);

            $data = $userdetails[0];
            return $this->sendResponse($data, 'Driver location ');
        }
    }

    /**
     * Helper to get user for booking based on source
     * source=0: by customer_id
     * source=1: by external_phone (find or null)
     */
    private function getBookingUser($booking)
    {
        if ($booking->source == 1) {
            // External booking: find user by phone or return null
            $user = User::where('phone', $booking->external_phone)->first();
            return $user;
        } else {
            // Normal booking: by customer_id
            return User::where('user_id', $booking->customer_id)->first();
        }
    }
    public function checkavailabledriver(Request $d)
    {
        $validator = Validator::make($d->all(), [
            'pincode' => 'required',


        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $pincodeid = Pincode::where("pincode", $d->input("pincode"))->get();
            $drivers = Driver::where("pincode", "Like", "%\"" . $pincodeid[0]->id . "\"%")->get();
            if (count($drivers) > 0) {
                foreach ($drivers as $driv) {
                    $countavailable = DB::table("booking")->where("status", "<=", 1)->where('accepted', $driv->id)->count();
                    if ($countavailable > 0) {
                        return $this->sendError("Driver Not Available");
                    } else {
                        return $this->sendResponse("Driver  Available", 'Driver  Available ');
                    }
                }
            } else {
                return $this->sendError("Driver Not Available");
            }
        }
    }
    public function otpverification(Request $d)
    {
        $validator = Validator::make($d->all(), [
            'booking_id' => 'required',
            "otp" => 'required'

        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $bookingdetails = DB::table("booking")->where('booking_id', $d->input('booking_id'))->get();
            if ($bookingdetails[0]->otp == $d->input('otp')) {
                DB::table("booking")->where('booking_id', $d->input('booking_id'))->update(['status' => 4]);
                $booking = Booking::where('booking_id', $d->input('booking_id'))?->first();
                $user = User::where('user_id', $booking?->customer_id)?->first();
                $content = "Chill with music 🎶 and enjoy a smooth ride!";
                $title = "Your Trip Has Started";
                $success = $this->notification($user, $booking, $title, $content);
                return $this->sendResponse("OTP Verified", 'OTP Verified and Ride started');
            } else {
                return $this->sendError('OTP is Invalid.');
            }
        }
    }

    public function otpcompleteverification(Request $d)
    {
        // Validate input
        $validator = Validator::make($d->all(), [
            'booking_id' => 'required',
            "otp" => 'required',
            'type' => 'nullable'
        ]);

        // Return validation error if it fails
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Fetch booking details
        $bookingdetails = DB::table("booking")->where('booking_id', $d->input('booking_id'))->first();

        // Check if booking exists
        if (!$bookingdetails) {
            return $this->sendError('Invalid Booking ID.');
        }

        // Verify OTP
        if ($bookingdetails->otp == $d->input('otp')) {
            // Update booking status
            DB::table("booking")->where('booking_id', $d->input('booking_id'))->update(['status' => 2]);

            // Fetch booking with related payment
            $booking = Booking::where('booking_id', $d->input('booking_id'))->with('bookingPayment')->first();
            $bookingCost = !empty($booking->bookingPayment) ? $booking->bookingPayment[0]->total : "0";

            // Fetch user details
            $user = User::where('user_id', $booking->customer_id)->first();

            // Send notification if user has a device token
            if (!empty($user->device_token)) {
                $content = "✔️ Have a great day! The fare for your trip was Rs. $bookingCost. You can also rate our service provider, who ensures your safety, service, and price control, while making sure you have a comfortable ride—it takes less than a minute!";
                $title = "You’ve Reached Your Destination";
                $this->notification($user, $booking, $title, $content);
            }

            // Fetch booking payment details
            $bookingPayment = BookingPayment::where('booking_id', $d->input('booking_id'))->first();

            // Update booking payment type if exists
            if ($bookingPayment) {
                $bookingPayment->update([
                    'type' => $d?->input('type')
                ]);
            }

            return $this->sendResponse("OTP Verified", 'OTP Verified and Ride Completed');
        } else {
            return $this->sendError('OTP is Invalid.');
        }
    }

    public function otpNotification($user, $bookingId)
    {
        $content = "Dear {{ $user->name }}, Otp Verification Completed successfully. Your Booking ID: {{ $bookingId }}";
        if (isset($user) && isset($user->device_token)) {
            $url = 'https://fcm.googleapis.com/fcm/send';
            $apiKey = "AAAAkWn7nBA:APA91bFram8BPeBiAofotOjsSYKXfnZjW3hFgjKdZjueKj_MHPzOOWHZ3CkT6y-2tu489P2Q0X5EBVKJz4Rpy78O3m-zKP_QsEvUQBV0N2HF6v_m7loQx0zo3yvuWSg8CTcWwvDO9RPC";

            // Compile headers in one variable
            $headers = array(
                'Authorization:key=' . $apiKey,
                'Content-Type:application/json'
            );

            // Add notification content to a variable for easy reference
            $notifData = [
                'title' => "Do N key",
                'body' =>  "OTP Verification Completed",
                //  "image": "url-to-image",//Optional
                'click_action' => "activities.NotifHandlerActivity" //Action/Activity - Optional
            ];

            $dataPayload = [
                'to' => 'My Name',
                'points' => 80,
                'other_data' => array(["data" => $content])
            ];

            // Create the api body
            $apiBody = [
                'notification' => $notifData,
                'data' => $dataPayload, //Optional
                // 'data' => array(["data" => $notification_data]),
                'time_to_live' => 600, // optional - In Seconds
                //'to' => '/topics/mytargettopic'
                //'registration_ids' = ID ARRAY
                'to' => $user->device_token,
            ];

            // Initialize curl with the prepared headers and body
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));

            // Execute call and save result
            $result = curl_exec($ch);
            // print($result);
            // Close curl after call
            curl_close($ch);
        }
        // return $d11[0]->device_token;
        return 1;
    }

    public function otpcompleteverificationbackup(Request $d)
    {
        $validator = Validator::make($d->all(), [
            'booking_id' => 'required',
            "otp" => 'required'

        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $bookingdetails = DB::table("booking")->where('booking_id', $d->input('booking_id'))->get();
            if ($bookingdetails[0]->otp == $d->input('otp')) {
                DB::table("booking")->where('booking_id', $d->input('booking_id'))->update(['status' => 2]);
                return $this->sendResponse("OTP Verified", 'OTP Verified and Ride Completed');
            } else {
                return $this->sendError('OTP is Invalid.');
            }
        }
    }

    public function getstatusofbooking(Request $d)
    {
        $validator = Validator::make($d->all(), [
            'booking_id' => 'required',


        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $bookingdetails = DB::table("booking")->where('booking_id', $d->input('booking_id'))->get();
            if ($bookingdetails[0]->status == 0) {
                $data['booking_status'] = "Searching For The Driver";
                $data['booking_id'] = $d->input('booking_id');
                return $this->sendResponse($data, 'Booking Status');
            }

            if ($bookingdetails[0]->status == 1) {
                $data['booking_status'] = " Driver Has Accepted the Order And He is On The Way";
                $data['booking_id'] = $d->input('booking_id');
                return $this->sendResponse($data, 'Booking Status');
            }
            if ($bookingdetails[0]->status == 2) {
                $data['booking_status'] = "Booking Has Been Completed";
                $data['booking_id'] = $d->input('booking_id');
                return $this->sendResponse($data, 'Booking Status');
            }
            if ($bookingdetails[0]->status == 4) {
                $data['booking_status'] = "Your Ride Has Started";
                $data['booking_id'] = $d->input('booking_id');
                return $this->sendResponse($data, 'Booking Status');
            }
            if ($bookingdetails[0]->status == 3) {
                $data['booking_status'] = "Booking has Been Cancelled";
                $data['booking_id'] = $d->input('booking_id');
                return $this->sendResponse($data, 'Booking Status');
            }
        }
    }

    // public function bookinghistory(Request $d)
    // {
    //     $validator = Validator::make($d->all(), [
    //         'source' => 'required|in:0,1',
    //         'user_id' => 'required_if:source,0',
    //         'external_phone' => 'required_if:source,1',
    //     ]);

    //     if ($validator->fails()) {
    //         return $this->sendError('Validation Error.', $validator->errors());
    //     }

    //     $source = (int) $d->get('source', 0);
    //     $user_id = null;
    //     $external_phone = null;

    //     if ($source === 0) {
    //         $user = User::where('id', $d->user_id)
    //             ->orWhere('user_id', $d->user_id)
    //             ->first(['user_id']);
    //         if (!$user) {
    //             return $this->sendError('User not found');
    //         }
    //         $user_id = $user->user_id;
    //     } else {
    //         $external_phone = $d->external_phone;
    //     }
    //     if ($source === 0) {
    //         $history = DB::table("booking")->where('customer_id', $user_id)->orderBy('created_at', 'desc')?->get(['*']);
    //     } else {
    //         $history = DB::table("booking")->where('external_phone', $external_phone)->orderBy('created_at', 'desc')?->get(['*']);
    //     }
    //     // $data['Booking history']=
    //     $book = [];
    //     foreach ($history as $h) {
    //         $booking_id = $h->booking_id;
    //         $book1 = DB::table("booking")->where('booking_id', $booking_id)?->get(['customer_id', 'category', 'distance', 'pincode', "booking_id", "status", "created_at"]);
    //         $book1[0]->time = Carbon::parse($book1[0]->created_at)->format('h:i A');

    //         if ($book1[0]->status == 0) {
    //             $bookstatus = "Searching For The Driver";
    //         }
    //         if ($book1[0]->status == 1) {
    //             $bookstatus = "Driver Has Accepted the Order And He is On The Way";
    //         }
    //         if ($book1[0]->status == 2) {
    //             $bookstatus = "Booking Has Been Completed";
    //         }
    //         if ($book1[0]->status == 3) {
    //             $bookstatus = "Booking has Been Cancelled";
    //         }
    //         if ($book1[0]->status == 4) {
    //             $bookstatus = "Your Ride Has Started";
    //         }
    //         $book1[0]->booking_status = $bookstatus;
    //         $user = User::where("user_id", $book1[0]->customer_id)->get(['name', 'email', 'phone', 'user_id', 'image']);
    //         $book2 = DB::table("booking_location_mapping")->where('booking_id', $booking_id)->get();
    //         if ($book2->isEmpty()) continue;
    //         $starting_location = DB::table("booking_locations")->where('location_id', $book2[0]->start_location_id)->get(['address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'landmark']);
    //         $endng_location = DB::table("booking_locations")->where('location_id', $book2[0]->end_location_id)->get(['address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'landmark']);
    //         $book3 = DB::table("booking_payment")->where('booking_id', $booking_id)->get(['total', 'base_price', 'tax', 'tax_split_1', 'tax_split_2', 'coupon_id', 'coupon_amount']);
    //         //$book3[0]->roundofftotal = round($book3[0]?->total);
    //         if (isset($book3[0])) {
    //             $book3[0]->roundofftotal = round($book3[0]->total);
    //         }
    //         // $user[0], $book1[0], array("starting_location" => $starting_location[0]), array("ending_location" => $endng_location[0]),array("price" => $book3[0])
    //         if ($book3[0]->coupon_id != null) {
    //             $book3[0]->total = $book3[0]?->total - $book3[0]?->coupon_amount;
    //         }
    //         array_push($book, array_merge(array("user" => $user[0]), array("bookingdetails" => $book1[0]), array("starting_location" => $starting_location[0]), array("ending_location" => $endng_location[0]), array("price" => $book3->isNotEmpty() ? $book3[0] : (object) []), array("booked_at" => $book1[0]->created_at), array("distance" => round($book1[0]->distance))));
    //     }
    //     return $this->sendResponse($book, 'Booking History');
    // }

    public function bookinghistory(Request $d)
    {
        $isExternal = (bool) $d->get('is_external', false)
            || $d->routeIs('external.bookinghistory')
            || str_starts_with((string) $d->path(), 'api/external/');

        $validator = Validator::make($d->all(), [
            'source' => 'nullable|in:0,1',
            'user_id' => $isExternal ? 'nullable' : 'required',
            'external_phone' => $isExternal ? 'required' : 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $source = $isExternal ? 1 : (int) $d->get('source', 0);

        if ($source === 0) {
            $user = User::where('id', $d->user_id)
                ->orWhere('user_id', $d->user_id)
                ->first(['user_id']);

            if (!$user) return $this->sendError('User not found');

            $history = DB::table("booking")
                ->where('customer_id', $user->user_id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $history = DB::table("booking")
                ->where('external_phone', $d->external_phone)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $book = [];

        foreach ($history as $h) {

            $booking = DB::table("booking")
                ->where('booking_id', $h->booking_id)
                ->first();

            if (!$booking) continue;


            $statusMap = [
                0 => "Searching For The Driver",
                1 => "Driver Has Accepted the Order And He is On The Way",
                2 => "Booking Has Been Completed",
                3 => "Booking has Been Cancelled",
                4 => "Your Ride Has Started"
            ];

            $booking->booking_status = $statusMap[$booking->status] ?? '';
            $booking->time = Carbon::parse($booking->created_at)->format('h:i A');


            $user = User::where("user_id", $booking->customer_id)
                ->first(['name', 'email', 'phone', 'user_id', 'image']);


            $mapping = DB::table("booking_location_mapping")
                ->where('booking_id', $h->booking_id)
                ->first();

            if (!$mapping) continue;


            $starting_location = DB::table("booking_locations")
                ->where('location_id', $mapping->start_location_id)
                ->first();

            $ending_location = DB::table("booking_locations")
                ->where('location_id', $mapping->end_location_id)
                ->first();


            $payment = DB::table("booking_payment")
                ->where('booking_id', $h->booking_id)
                ->first();

            if ($payment) {
                $payment->roundofftotal = round($payment->total);

                if ($payment->coupon_id) {
                    $payment->total -= $payment->coupon_amount;
                }
            }

            $book[] = [
                "user" => $user ?? (object)[],
                "bookingdetails" => $booking,
                "starting_location" => $starting_location ?? (object)[],
                "ending_location" => $ending_location ?? (object)[],
                "price" => $payment ?? (object)[],
                "booked_at" => $booking->created_at,
                "distance" => round($booking->distance)
            ];
        }

        return $this->sendResponse($book, 'Booking History');
    }


    public function sendnotificationtootherdriverbackup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required',
            'radius' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $bookingId = $request->input('booking_id');
        $booking = DB::table("booking")->where('booking_id', $bookingId)->first();
        $bookingLocation = DB::table("booking_locations")->where('booking_id', $bookingId)->first();

        if (!$booking || !$bookingLocation) {
            return $this->sendError('Booking or Booking Location not found');
        }

        $bookingLatitude = $bookingLocation->lat;
        $bookingLongitude = $bookingLocation->long;
        $pincode = $booking->pincode;
        $user_id = $booking->customer_id;

        $notificationData = [
            'booking_id' => $bookingId,
            'user_id' => $user_id,
            'distance' => $booking->distance,
            'pincode' => $pincode
        ];

        // Get drivers within a certain radius based on their latitude and longitude
        $radius = $request->input('radius'); // Radius in meters

        $driversWithinRadius = Driver::select('*')
            ->selectRaw("( 6371000 * acos( cos( radians($bookingLatitude) ) *
               cos( radians( lat ) )
               * cos( radians( `long` ) - radians($bookingLongitude) )
               + sin( radians($bookingLatitude) ) *
               sin( radians( lat ) ) ) ) AS distance")
            ->having('distance', '<=', $radius)
            ->get();

        // Debugging: Log the drivers within the radius
        Log::info("Drivers within radius: " . count($driversWithinRadius));
        //return $driversWithinRadius;
        // Send notifications to drivers within the radius
        foreach ($driversWithinRadius as $driver) {
            $user = Enduser::where('id', $driver->userid)->where('is_live', 1)->first();
            $deviceToken = $user ? $user->device_token : null;

            if (isset($deviceToken)) {
                // Send notification using FCM or any other service
                $this->sendNotification($deviceToken, $notificationData);
            }
        }

        return $this->sendResponse(['booking_id' => $bookingId], 'Notification Sent to Other Drivers');
    }

    public function sendnotificationtootherdriver28(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required',
            'radius' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $bookingId = $request->input('booking_id');
        $booking = DB::table("booking")->where('booking_id', $bookingId)->first();
        $bookingLocation = DB::table("booking_locations")->where('booking_id', $bookingId)->first();

        if (!$booking || !$bookingLocation) {
            return $this->sendError('Booking or Booking Location not found');
        }

        $bookingLatitude = $bookingLocation->lat;
        $bookingLongitude = $bookingLocation->long;
        $pincode = $booking->pincode;
        $user_id = $booking->customer_id;

        $notificationData = [
            'booking_id' => $bookingId,
            'user_id' => $user_id,
            'distance' => $booking->distance,
            'pincode' => $pincode
        ];

        // Get drivers within a certain radius based on their latitude and longitude
        $drivers = Driver::all(); // Assuming you have latitude and longitude columns in the Driver model

        $radius = $request->input('radius'); // Radius in meters
        $driversWithinRadius = [];

        foreach ($drivers as $driver) {
            // Calculate distance between booking location and driver location
            $driverLatitude = $driver->lat;
            $driverLongitude = $driver->long;
            $distanceToBooking = $this->calculateDistance($bookingLatitude, $bookingLongitude, $driverLatitude, $driverLongitude);

            // Debugging: Log the calculated distance
            Log::info("Distance to driver: $distanceToBooking meters");

            if ($distanceToBooking <= $radius) {
                $driversWithinRadius[] = $driver;
            }
        }
        //return $driversWithinRadius;
        // Debugging: Log the drivers within the radius
        Log::info("Drivers within radius: " . count($driversWithinRadius));

        // Send notifications to drivers within the radius
        //return $driversWithinRadius;
        //$driversWithinRadius2 = [];
        foreach ($driversWithinRadius as $driver) {
            $user = Enduser::where('id', $driver?->userid)?->where('is_live', 1)?->first();
            $deviceToken = $user?->device_token;

            $subscriber = DB::table("subscriber")->where('id', $driver?->subscriberId)->first();
            if (($subscriber?->activestatus == 1) && ($subscriber?->blockedstatus == 1)) {
                if (isset($deviceToken)) {
                    // Send notification using FCM or any other service
                    $this->sendNotification($deviceToken, $notificationData);
                }
            }
        }
        //return $driversWithinRadius2;
        return $this->sendResponse(['booking_id' => $bookingId], 'Notification Sent to Other Drivers');
    }

    public function sendnotificationtootherdriver(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required',
            'radius' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $bookingId = $request->input('booking_id');
        $booking = DB::table("booking")->where('booking_id', $bookingId)->first();
        $bookingLocation = DB::table("booking_locations")->where('booking_id', $bookingId)->first();

        if (!$booking || !$bookingLocation) {
            return $this->sendError('Booking or Booking Location not found');
        }

        $bookingLatitude = $bookingLocation->lat;
        $bookingLongitude = $bookingLocation->long;
        $pincode = $booking->pincode;
        $user_id = $booking->customer_id;

        $notificationData = [
            'booking_id' => $bookingId,
            'user_id' => $user_id,
            'distance' => $booking->distance,
            'pincode' => $pincode
        ];

        // Get drivers within a certain radius based on their latitude and longitude
        $drivers = Driver::all(); // Assuming you have latitude and longitude columns in the Driver model

        $radius = $request->input('radius'); // Radius in meters
        $driversWithinRadius = [];

        foreach ($drivers as $driver) {
            // Calculate distance between booking location and driver location
            $driverLatitude = $driver->lat;
            $driverLongitude = $driver->long;
            $distanceToBooking = $this->calculateDistance($bookingLatitude, $bookingLongitude, $driverLatitude, $driverLongitude);

            // Debugging: Log the calculated distance
            Log::info("Distance to driver: $distanceToBooking meters");

            if ($distanceToBooking <= $radius) {
                $driver->is_radius = 1;
                $driver->save();
                $driversWithinRadius[] = $driver;
            }
        }
        //return $driversWithinRadius;
        // Debugging: Log the drivers within the radius
        Log::info("Drivers within radius: " . count($driversWithinRadius));

        // Send notifications to drivers within the radius
        //return $driversWithinRadius;
        //$driversWithinRadius2 = [];
        foreach ($driversWithinRadius as $driver) {
            $user = Enduser::where('id', $driver?->userid)?->where('is_live', 1)?->first();
            $deviceToken = $user?->device_token;

            $subscriber = DB::table("subscriber")->where('id', $driver?->subscriberId)->first();
            if (($subscriber?->activestatus == 1) && ($subscriber?->blockedstatus == 1)) {
                if (isset($deviceToken)) {
                    // Send notification using FCM or any other service
                    $this->sendNotification($deviceToken, $notificationData);
                }
            }
        }
        //return $driversWithinRadius2;
        return $this->sendResponse(['booking_id' => $bookingId], 'Notification Sent to Other Drivers');
    }


    // Utility function to calculate distance between two points using Haversine formula
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meters
        $deltaLat = deg2rad($lat2 - $lat1);
        $deltaLon = deg2rad($lon2 - $lon1);
        $a = sin($deltaLat / 2) * sin($deltaLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($deltaLon / 2) * sin($deltaLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;
        return $distance;
    }

    private function sendNotification28($deviceToken, $notificationData)
    {
        // Your FCM server key
        $serverKey = "AAAAkWn7nBA:APA91bFram8BPeBiAofotOjsSYKXfnZjW3hFgjKdZjueKj_MHPzOOWHZ3CkT6y-2tu489P2Q0X5EBVKJz4Rpy78O3m-zKP_QsEvUQBV0N2HF6v_m7loQx0zo3yvuWSg8CTcWwvDO9RPC";

        // URL for FCM API endpoint
        $url = 'https://fcm.googleapis.com/fcm/send';

        // Headers for the request
        $headers = [
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json',
        ];

        // Notification message data
        $notification = [
            'title' => "do N key",
            'body' => "Passenger Needed To Travel to a \n  Distance :" . $notificationData['distance'] . "\n" . "Pincode :" . $notificationData['pincode'],
            'click_action' => "activities.NotifHandlerActivity" //Action/Activity - Optional
        ];

        // Payload data for the notification
        $data = [
            'booking_id' => $notificationData['booking_id'],
            'user_id' => $notificationData['user_id'],
            'distance' => $notificationData['distance'],
            'pincode' => $notificationData['pincode'],
        ];

        // Data payload for the request
        $payload = [
            'to' => $deviceToken,
            'notification' => $notification,
            'data' => $data,
        ];

        // Initialize curl with the prepared headers and body
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        // Execute call and save result
        $result = curl_exec($ch);

        // Check for curl errors
        if ($result === false) {
            $error = curl_error($ch);
            // Log the error
            error_log('Curl error: ' . $error);
            // Handle the error (e.g., return false, throw an exception, etc.)
            return false;
        }

        // Close curl after call
        curl_close($ch);

        // Return the result (success or failure)
        return $result;
    }

    private function sendNotification($deviceToken, $notificationData)
    {
        $url = 'https://fcm.googleapis.com/v1/projects/donkey-driver/messages:send';
        // Authorization token (replace with your actual bearer token)
        $driverToken = site::where('id', 1)?->first()?->driverToken;
        $token = $driverToken;

        // Create HTTP client instance
        $client = new Client();

        // Headers for the request
        $headers = [
            'Authorization' => $token,
            'Content-Type' => 'application/json',
        ];

        $notifData = [
            'title' => "do N key",
            'body' => "Passenger Needed To Travel to a \nDistance: " . $notificationData['distance'] . "\nPincode: " . $notificationData['pincode'],
        ];

        // Payload data for the notification
        $dataPayload = [
            'booking_id' => $notificationData['booking_id'],
            'user_id' => $notificationData['user_id'],
            'distance' => $notificationData['distance'],
            'pincode' => $notificationData['pincode'],
        ];

        // API body payload with notification and platform-specific settings
        $apiBody = [
            'message' => [
                'token' => $deviceToken, // Target device token
                'notification' => $notifData,  // Notification section
                'android' => [
                    'priority' => 'high',
                    'notification' => [
                        'sound' => 'default',
                        'channel_id' => '1101'
                    ]
                ],
                'apns' => [
                    'headers' => [
                        'apns-priority' => '10', // iOS notification priority
                    ],
                ],
                'data' => $dataPayload // Data section
            ]
        ];

        // Make a POST request to FCM API
        $response = $client->post($url, [
            'headers' => $headers,
            'json' => $apiBody,
        ]);

        // Check response status code
        if ($response->getStatusCode() === 200) {
            // Notification sent successfully
            Log::info('Notification sent successfully');
            return true;
        } else {
            // Log unsuccessful responses
            Log::error('Failed to send notification. Status code: ' . $response->getStatusCode());
            return false;
        }
    }

    private function sendNotificationbackup29($deviceToken, $notificationData)
    {
        // Your FCM server key
        $serverKey = "AAAAkWn7nBA:APA91bFram8BPeBiAofotOjsSYKXfnZjW3hFgjKdZjueKj_MHPzOOWHZ3CkT6y-2tu489P2Q0X5EBVKJz4Rpy78O3m-zKP_QsEvUQBV0N2HF6v_m7loQx0zo3yvuWSg8CTcWwvDO9RPC";

        // URL for FCM API endpoint
        $url = 'https://fcm.googleapis.com/fcm/send';

        // Create HTTP client instance
        $client = new Client();

        // Headers for the request
        $headers = [
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json',
        ];

        // Notification message data
        $notification = [
            'title' => "do N key",
            'body' => "Passenger Needed To Travel to a \n  Distance :" . $notificationData['distance'] . "\n" . "Pincode :" . $notificationData['pincode'],
            //  "image": "url-to-image",//Optional
            // 'body' => "hai",
            //'click_action' => "activities.NotifHandlerActivity" //Action/Activity - Optional
        ];

        // Payload data for the notification
        $data = [
            'booking_id' => $notificationData['booking_id'],
            'user_id' => $notificationData['user_id'],
            'distance' => $notificationData['distance'],
            'pincode' => $notificationData['pincode'],
        ];

        // Data payload for the request
        $payload = [
            'to' => $deviceToken,
            'notification' => $notification,
            'data' => $data,
        ];

        // Make a POST request to FCM API
        $response = $client->post($url, [
            'headers' => $headers,
            'json' => $payload,
        ]);
    }

    // Utility function to send error response
    public function sendError($message, $errors = [], $code = 404)
    {
        return response()->json([
            'error' => $message,
            'errors' => $errors,
        ], $code);
    }

    // Utility function to send success response
    public function sendResponse($data, $message = 'Success')
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ]);
    }

    public function sendnotificationtootherdriver2(Request $d)
    {
        $validator = Validator::make($d->all(), [
            'booking_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $booking_id = $d->input('booking_id');
            $pincodedatacount = DB::table("booking")->where('booking_id', $booking_id)->count();

            if ($pincodedatacount > 0) {
                $pincodedata = DB::table("booking")->where('booking_id', $booking_id)->get();
                $pincode = $pincodedata[0]->pincode;
                $user_id = $pincodedata[0]->customer_id;
                $distance = $pincodedata[0]->distance;

                $notification_data = array($booking_id,  $user_id, $distance, $pincode);
                $driverarray = [];

                $driver = Driver::get(['pincode', 'id', 'userid']);

                foreach ($driver as $drivers) {
                    $driverpin =  json_decode($drivers->pincode);

                    foreach ($driverpin as $p) {
                        if ($p != $pincode) {
                            array_push($driverarray, $drivers->userid);
                        }
                    }
                }

                $driverarray = array_unique($driverarray);

                if (count($driverarray) > 0) {
                    foreach ($driverarray as $da) {
                        $d11 = User::where('id', $da)->get(['device_token']);

                        if (count($d11) > 0 && $d11[0]->device_token != "") {
                            $url = 'https://fcm.googleapis.com/fcm/send';

                            $apiKey = "AAAAkWn7nBA:APA91bFram8BPeBiAofotOjsSYKXfnZjW3hFgjKdZjueKj_MHPzOOWHZ3CkT6y-2tu489P2Q0X5EBVKJz4Rpy78O3m-zKP_QsEvUQBV0N2HF6v_m7loQx0zo3yvuWSg8CTcWwvDO9RPC";

                            $headers = array(
                                'Authorization:key=' . $apiKey,
                                'Content-Type:application/json'
                            );

                            $notifData = [
                                'title' => "do N key",
                                'body' => "Passenger Needed To Travel to a \n  Distance :" . $notification_data[2] . "\n" . "Pincode :" . $notification_data[3],
                                'click_action' => "activities.NotifHandlerActivity"
                            ];

                            $dataPayload = [
                                'to' => $d11[0]->device_token,
                                'notification' => $notifData,
                                'data' => [
                                    'booking_id' => $booking_id,
                                    'user_id' => $user_id,
                                    'distance' => $distance,
                                    'pincode' => $pincode
                                ]
                            ];

                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataPayload));

                            $result = curl_exec($ch);
                            curl_close($ch);
                        }
                    }
                }

                $data['booking_id'] = $booking_id;
                return $this->sendResponse($data, 'Notification Send to Other Drivers');
            } else {
                return $this->sendError('There is No Such Booking');
            }
        }
    }

    // Utility functions
    public function sendError2($message, $errors = [], $code = 404)
    {
        return response()->json([
            'error' => $message,
            'errors' => $errors,
        ], $code);
    }

    public function sendResponse2($data, $message = 'Success')
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ]);
    }


    public function sendnotificationtootherdriver1(Request $d)
    {
        $validator = Validator::make($d->all(), [
            'booking_id' => 'required',


        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $booking_id = $d->get('booking_id');
            $pincodedatacount = DB::table("booking")->where('booking_id', $booking_id)->count();
            if ($pincodedatacount > 0) {
                $pincodedata = DB::table("booking")->where('booking_id', $booking_id)->get();
                // return $pincode;
                $pincode = $pincodedata[0]->pincode;
                $user_id = $pincodedata[0]->customer_id;
                $distance = $pincodedata[0]->distance;

                $notification_data = array($booking_id,  $user_id, $distance, $pincode);
                $driverarray = [];
                $driver = Driver::get(['pincode', 'id', 'userid']);
                foreach ($driver as $drivers) {
                    $driverpin =  json_decode($drivers->pincode);
                    foreach ($driverpin as $p) {
                        if ($p != $pincode) {
                            //notification for that driver pincode
                            // $user_id = User::where('id', $drivers->id)->get(['user_id']);
                            // if (count($user_id) > 0)

                            array_push($driverarray, $drivers->userid);
                        }
                    }
                }

                $driverarray = array_unique($driverarray);
                if (count($driverarray) > 0) {
                    // return $this->sendResponse(array_unique($driverarray), 'These Are the driver id for the notification');
                    foreach ($driverarray as $da) {
                        $d11 = User::where('id', $da)->get(['device_token']);
                        if (count($d11) > 0 && $d11[0]->device_token != "") {
                            $url = 'https://fcm.googleapis.com/fcm/send';

                            // Put your Server Key here
                            $apiKey = "AAAAkWn7nBA:APA91bFram8BPeBiAofotOjsSYKXfnZjW3hFgjKdZjueKj_MHPzOOWHZ3CkT6y-2tu489P2Q0X5EBVKJz4Rpy78O3m-zKP_QsEvUQBV0N2HF6v_m7loQx0zo3yvuWSg8CTcWwvDO9RPC";
                            // $apiKey = "AAAA7r-3Egc:APA91bE4i_fLddPNSBHNw298mrXNrrHDiSS3WqsIEZKSxPEm9MWv-UmB4YhfrccTsMM_XsVEWJCs0d2JIGVw52ivUnRhlTJMV0Yyb4msIeP5Yt1sycK4FRaL_1e6K9dEOyDGTGOv01N4";

                            // Compile headers in one variable
                            $headers = array(
                                'Authorization:key=' . $apiKey,
                                'Content-Type:application/json'
                            );

                            // Add notification content to a variable for easy reference
                            $notifData = [
                                'title' => "do N key",
                                'body' => "Passenger Needed To Travel to a \n  Distance :" . $notification_data[2] . "\n" . "Pincode :" . $notification_data[3],
                                //  "image": "url-to-image",//Optional
                                // 'body' => "hai",
                                'click_action' => "activities.NotifHandlerActivity" //Action/Activity - Optional
                            ];

                            $dataPayload = [
                                'to' => 'My Name',
                                'points' => 80,
                                'other_data' => array(["data" => $notification_data])
                            ];

                            // Create the api body
                            $apiBody = [
                                'notification' => $notifData,
                                'data' => $dataPayload, //Optional
                                // 'data' => array(["data" => $notification_data]),
                                'time_to_live' => 600, // optional - In Seconds
                                //'to' => '/topics/mytargettopic'
                                //'registration_ids' = ID ARRAY
                                'to' => $d11[0]->device_token,
                            ];

                            // Initialize curl with the prepared headers and body
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));

                            // Execute call and save result
                            $result = curl_exec($ch);
                            // print($result);
                            // Close curl after call
                            curl_close($ch);
                        }
                    }
                }
                $data['booking_id'] = $booking_id;
                return $this->sendResponse($data, 'Notification Send to Other Drivers');
            } else {
                return $this->sendError('There is No Such Booking');
            }
        }
    }

    public function driverhistory(Request $d)
    {
        $validator = Validator::make($d->all(), [
            'driver_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            //            $history= DB::table("booking")->where("accepted",$d->get('driver_id'))->orWhere("ignored",'LIKE','%,'.$d->get('driver_id').',%')->orderBy('created_at','desc')->get();

            // $history= DB::table("booking")->where("accepted",$d->get('driver_id'))->orderBy('created_at','desc')->get();
            $history = Booking::where('accepted', $d->driver_id)
                ->when(request('date'), function ($query, $date) {
                    $query->where(function ($query) use ($date) {
                        $query->whereDate('updated_at', '=', $date);
                    });
                })
                ->latest()->get();
            // return $history;
            $book = [];
            foreach ($history as $h) {
                $booking_id = $h->booking_id;
                $book1 = DB::table("booking")->where('booking_id', $booking_id)->get(['customer_id', 'category', 'distance', 'pincode', "booking_id", "status", 'updated_at']);
                if ($book1[0]->status == 0) {
                    $bookstatus = "Searching For The Driver";
                }
                if ($book1[0]->status == 1) {
                    $bookstatus = "Driver Has Accepted the Order And He is On The Way";
                }
                if ($book1[0]->status == 2) {
                    $bookstatus = "Booking Has Been Completed";
                }
                if ($book1[0]->status == 3) {
                    $bookstatus = "Booking has Been Cancelled";
                }
                if ($book1[0]->status == 4) {
                    $bookstatus = "Your Ride Has Started";
                }
                $book1[0]->booking_status = $bookstatus;
                $user = User::where("user_id", $book1[0]->customer_id)->get(['name', 'email', 'phone', 'user_id', 'image']);
                $book2 = DB::table("booking_location_mapping")->where('booking_id', $booking_id)->get();
                $starting_location = DB::table("booking_locations")->where('location_id', $book2[0]->start_location_id)->get(['address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'landmark']);
                $endng_location = DB::table("booking_locations")->where('location_id', $book2[0]->end_location_id)->get(['address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'landmark']);
                $book3 = DB::table("booking_payment")->where('booking_id', $booking_id)->get(['total', 'base_price', 'tax', 'tax_split_1', 'tax_split_2', 'coupon_id', 'coupon_amount']);
                $book3[0]->roundofftotal = round($book3[0]->total);
                if (isset($book3[0]->coupon_id)) {
                    $book3[0]->total = $book3[0]->total - $book3[0]->coupon_amount;
                }
                // $user[0], $book1[0], array("starting_location" => $starting_location[0]), array("ending_location" => $endng_location[0]),array("price" => $book3[0])
                array_push($book, array_merge(array("user" => $user[0]), array("bookingdetails" => $book1[0]), array("starting_location" => $starting_location[0]), array("ending_location" => $endng_location[0]), array("price" => $book3[0]), array("booked_at" => $book1[0]->updated_at), array("distance" => round($book1[0]->distance))));
            }
            return $this->sendResponse($book, 'Booking History of driver');
            // return $this->sendResponse($data, 'Notification Send to Other Drivers');
        }
    }

    public function drivertocustomercall(Request $d)
    {
        $validator = Validator::make($d->all(), [
            'booking_id' => 'required',


        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $details = DB::table("booking")->where("booking_id", $d->get('booking_id'))->get(['customer_id']);
            $mobile = User::where("user_id", $details[0]->customer_id)->get(['phone']);
            //  return gettype($mobile[0]->phone);
            return $this->sendResponse($mobile, 'Customer Phone Number For Driver');
        }
    }







    public function bookingtaxi1(Request $d)
    {
        // return Hash::make('hai');
        $validate = Validator::make($d->all(), [
            'user_id' => "required",
            'distance' => "required",
            'pincode' => "required",
            'category' => "required",

            'description' => "required",
            'from_location' => "required",
            'to_location' => "required",
            // 'address1' => "required",
            // 'address2' => "required",
            // 'category' => "required",
            // 'city' => "required",
            // 'state' => "required",
            // 'country' => "required",
            // 'postal_code' => "required",
            // 'lat' => "required",
            // 'long' => "required",
            // 'landmark' => "required",


        ]);
        if ($validate->fails()) {
            return $this->sendError($validate->errors());
        } else {
            $checkthebookinghistory1 = DB::table('booking')->where('customer_id', $d->get('user_id'))->orderBy('created_at', 'desc')->get();

            if (count($checkthebookinghistory1) > 0) {
                $checkthebookinghistory = DB::table('booking')->where('customer_id', $d->get('user_id'))->orderBy('created_at', 'desc')->first();

                if ($checkthebookinghistory->status == 4 || $checkthebookinghistory->status == 1 || $checkthebookinghistory->status == 0) {
                    return $this->sendError("Sorry You Have Already Booked", array("bookingallowingstatus" => 1));
                }
            }

            $booking_id = 'doc-' . $this->guidv4();
            $from_location_id = 'loc-' . time() . '-' . mt_rand();
            $to_location_id = 'loc-' . time() . '-' . mt_rand();
            $notification_data = array($booking_id, $from_location_id, $to_location_id, $d->get('user_id'), $d->get('distance'), $d->get('pincode'));
            $otp = rand(1000, 9999);
            DB::table('booking')->insert([
                "booking_id" => $booking_id,
                "customer_id" => $d->get('user_id'),
                "status" => 0,
                "category" => $d->get('category'),
                "distance" => $d->get('distance'),
                "pincode" => $d->get('pincode'),
                "otp" => $otp,
            ]);
            DB::table('booking_history')->insert([
                "booking_id" => $booking_id,
                "user_id" => $d->get('user_id'),
                "action" => "BOOKING CREATE",
                "description" => $d->get('description'),
                "type" => 0,
                // "pincode" => $d->get('pincode'),
            ]);
            // $address1[] = $d->get('address1');
            // $address2[] = $d->get('address2');
            // $city[] = $d->get('city');
            // $state[] = $d->get('state');
            // $country[] = $d->get('country');
            // $postal_code[] = $d->get('postal_code');
            // $lat[] = $d->get('lat');
            // $long[] = $d->get('long');
            // $landmark[] = $d->get('landmark');
            DB::table('booking_locations')->insert([
                "booking_id" => $booking_id,
                "location_id" => $from_location_id,
                "address1" => $d->get('from_location')['address1'],
                "address2" => $d->get('from_location')['address2'],
                "address3" => $d->get('from_location')['address3'],
                // "from_location" => $d->get('from_location'),
                // "to_location" => $d->get('to_location'),
                "city" => $d->get('from_location')['city'],
                "state" => $d->get('from_location')['state'],
                "country" => $d->get('from_location')['country'],
                "postal_code" => $d->get('from_location')['postal_code'],
                "lat" => $d->get('from_location')['lat'],
                "long" => $d->get('from_location')['long'],
                "landmark" => $d->get('from_location')['landmark'],
            ]);
            $to_location = $d->get('to_location');
            $address1 = [];
            $address2 = [];
            $city = [];
            $address3 = [];
            $state = [];
            $country = [];
            $postal_code = [];
            $lat = [];
            $long = [];
            $landmark = [];
            foreach ($to_location as $to_loc) {
                // return $to_loc['address1'];
                array_push($address1, $to_loc['address1']);
                array_push($address2, $to_loc['address2']);
                array_push($address3, $to_loc['address3']);
                array_push($city, $to_loc['city']);
                array_push($state, $to_loc['state']);
                array_push($country, $to_loc['country']);
                array_push($postal_code, $to_loc['postal_code']);
                array_push($lat, $to_loc['lat']);
                array_push($long, $to_loc['long']);
                array_push($landmark, $to_loc['landmark']);
            }
            DB::table('booking_locations')->insert([
                "booking_id" => $booking_id,
                "location_id" => $to_location_id,
                // "from_location" => $d->get('from_location'),
                // "to_location" => $d->get('to_location'),
                // "address1" => $d->get('address1'),
                // "address2" => $d->get('address2'),
                // "address3" => $d->get('address2'),
                // "city" => $d->get('city'),
                // "state" => $d->get('state'),
                // "country" => $d->get('country'),
                // "postal_code" => $d->get('postal_code'),
                // "lat" => $d->get('lat'),
                // "long" => $d->get('long'),
                // "landmark" => $d->get('landmark'),
                "address1" => implode(',', $address1),
                "address2" => implode(',', $address2),
                "address3" => implode(',', $address3),

                "city" => implode(',', $city),
                "state" => implode(',', $state),
                "country" => implode(',', $country),
                "postal_code" => implode(',', $postal_code),
                "lat" => implode(',', $lat),
                "long" => implode(',', $long),
                "landmark" => implode(',', $landmark),
            ]);

            DB::table('booking_location_mapping')->insert([
                "booking_id" => $booking_id,
                "end_location_id" => $to_location_id,
                "start_location_id" => $from_location_id,

            ]);
            // $price = DB::table('price')->where('pincode', $d->get('pincode'))->where('category', $d->get('category'))->where('range_from', "<=", $d->get('distance'))->where('range_to', ">", $d->get('distance'))->get();

            $distance = $d->get('distance');
            $category = $d->get('category');
            $pincode = $d->get('pincode');
            $price =  DB::table('price')->where(['category' => $category, 'pincode' => $pincode])
                ->where('range_from', '<', $distance)
                ->where('range_to', '>=', $distance)
                ->get();
            // $total = round(($price[0]->amount*$d->get('distance')) +((($price[0]->amount*$d->get('distance'))/100)* $price[0]->tax));
            // $total=$this->calculatetotal(($price[0]->amount*$d->get('distance')),$d->get('category'),$price[0]->subscriber_id);

            DB::table('booking_payment')->insert([
                "booking_id" => $booking_id,
                "base_price" => round(($price[0]->amount * $d->get('distance'))),
                // "tax" => $price[0]->tax,
                "tax" => round($this->calculatetotaltax(round(($price[0]->amount * $d->get('distance'))), $d->get('category'), $price[0]->subscriber_id)),

                "tax_split_1" => $price[0]->tax_split_1,
                "tax_split_2" => $price[0]->tax_split_2,
                // "total" => $total,
                "total" => round($this->calculatetotal(round(($price[0]->amount * $d->get('distance'))), $d->get('category'), $price[0]->subscriber_id)),
                // "round_off" => $round_off,

            ]);
        }

        $val = $this->notificationtodriverfirebase1($d->get('pincode'), $notification_data);

        // return array(["data" => $notification_data]);
        if ($val == 1) {

            $bid = $notification_data[0];
            $de = DB::table('booking')->where("booking_id", $bid)->get(['accepted']);
            $driverd = DB::table('driver')->where('userid', $de[0]->accepted)->get(['userid', 'email', 'name', 'mobile']);
            $data['otp'] = $otp;
            $data['driver_details'] = $driverd;
            $data['driver_accepted_status'] = 1;
            $data['booking_id'] = $booking_id;
            $data["bookingallowingstatus"] = 0;
            return $this->sendResponse($data, "Booking Has Been Successfull");
        } else {
            $data['otp'] = $otp;
            $data['booking_id'] = $booking_id;
            $data["bookingallowingstatus"] = 0;
            $data['driver_accepted_status'] = 0;
            return $this->sendResponse($data, "Booking Has Been Successfull Waiting For Accepting");
        }
        // return $this->sendResponse($otp, "Booking Has Been Successfull");
    }

    public function checkNumber(Request $request)
    {
        $validator = validator($request->all(), [
            'mobile' => 'required'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // If validation fails, return a JSON response with errors
            return response()->json(['error' => 'Mobile Number is required', 'validation_errors' => $validator->errors()], 422);
        }

        // Validation passed, proceed with the rest of the logic
        $user = User::where('phone', $request->input('mobile'))->first();

        if (!empty($user) && $user->is_googleUser == 0) {
            return response()->json(['status' => true, 'message' => 'User found']);
        } elseif ($user->is_googleUser == 1) {
            return response()->json(['status' => false, 'message' => 'google User']);
        } else {
            return response()->json(['status' => false, 'message' => 'User not found']);
        }
    }

    public function userProfile(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
        ]);
        $userDetails = Enduser::where('user_id', $validated['user_id'])->select('user_id', 'name', 'phone', 'email', 'gender', 'image', 'dob')?->first();
        $home_address = UserAddress::where('user_id', $userDetails?->id)?->where('type', 1)->first();
        $office_address = UserAddress::where('user_id', $userDetails?->id)?->where('type', 2)->first();
        $userAddress['home'] = $home_address;
        $userAddress['office'] = $office_address;
        return new JsonResponse([
            'status' => true,
            'user_details' => $userDetails,
            'user_address' => $userAddress
        ]);
    }

    public function updateProfile(Request $request, $id)
    {
        $validator = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'home_address' => 'nullable',
            'type1' => 'required_if:home_address,!=,null',
            'office_address' => 'nullable',
            'type2' => 'required_if:office_address,!=,null',
            'image' => 'nullable'
        ]);
        if ($request->has('image')) {
            $imageDecode = base64_decode($validator['image']);
            if ($imageDecode !== false) {
                $imageName = uniqid() . ".jpeg";
                file_put_contents(public_path('userprofile') . '/' . $imageName, $imageDecode);
                $validator['image'] = $imageName;
            }
        }

        $userDetails = Enduser::where('user_id', $id)?->first();

        if (isset($userDetails)) {
            if ($request->has('image')) {
                $userDetails->update([
                    'name' => $validator['name'],
                    'email' => $validator['email'],
                    'phone' => $validator['phone'],
                    'dob' => $validator['dob'],
                    'gender' => $validator['gender'],
                    'image' => $validator['image']
                ]);
            } else {
                $userDetails->update([
                    'name' => $validator['name'],
                    'email' => $validator['email'],
                    'phone' => $validator['phone'],
                    'dob' => $validator['dob'],
                    'gender' => $validator['gender'],
                    // 'image' => $validator['image']
                ]);
            }
            if ($request->has('home_address')) {
                $home_address = UserAddress::where('user_id', $id)->where('type', 1)?->first();
                if (isset($home_address)) {
                    $home_address['address1'] = $validator['home_address'];
                    $home_address->update();
                } else {
                    $home_address = UserAddress::create([
                        'user_id' => $id,
                        'address1' => $validator['home_address'],
                        'type' => 1
                    ]);
                }
                $office_address = UserAddress::where('user_id', $id)->where('type', 2)?->first();
                if (isset($office_address)) {
                    $office_address['address1'] = $validator['office_address'];
                    $office_address->update();
                } else {
                    $office_address = UserAddress::create([
                        'user_id' => $id,
                        'address1' => $validator['office_address'],
                        'type' => 2
                    ]);
                }
            }
            $useraddress['home'] = $home_address;
            $useraddress['office'] = $office_address;
            return new JsonResponse([
                'status' => true,
                'message' => 'User Updated Successfully',
                'user_details' => $userDetails,
                'user_address' => $useraddress
            ]);
        } else {
            return new JsonResponse([
                'status' => false,
                'message' => 'User Not Found',
            ]);
        }
    }

    public function updateMobileNumber(Request $request, $id)
    {
        $userDetails = Enduser::where('user_id', $id)?->first();
        if (isset($userDetails)) {

            $validator = $request->validate([
                'phone' => ['required', Rule::unique(Enduser::class)->ignore($userDetails->id)],
            ]);

            $userDetails->update([
                'phone' => $validator['phone'],
            ]);

            return new JsonResponse([
                'status' => true,
                'message' => 'Mobile Number Updated Successfully',
            ]);
        } else {
            return new JsonResponse([
                'status' => false,
                'message' => 'User Not Found',
            ]);
        }
    }

    public function addAddress(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable',
            'user_id' => 'required|exists:users,user_id',
            'address1' => 'required', // Corrected 'requird' to 'required'
            'type' => 'required',
        ]);

        $home = UserAddress::where('user_id', $data['user_id'])->where('type', 1)?->first();
        $office = UserAddress::where('user_id', $data['user_id'])->where('type', 2)?->first();
        if ($data['type'] == 1) {
            if (isset($home)) {
                $home->address1 = $data['address1'];
                $home->update();
                return new JsonResponse([
                    'status' => true,
                    'message' => 'Home Address Updated Successfully',
                ]);
            }
        } elseif ($data['type'] == 2) {
            if (isset($office)) {
                $office->address1 = $data['address1'];
                $office->update();
                return new JsonResponse([
                    'status' => false,
                    'message' => 'Office Address Already Exists',
                ]);
            }
        }

        $address = UserAddress::create($data);
        return new JsonResponse([
            'status' => true,
            'message' => 'address stored successfully',
            'favourite_address' => $address
        ]);
    }

    public function favouriteAddressList(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
        ]);
        $address = UserAddress::where('user_id', $validated['user_id'])->where('type', 3)->get();
        return new JsonResponse([
            'status' => true,
            'message' => 'Favourite Address List',
            "favourite_address's" => $address
        ]);
    }

    public function deleteAddress(Request $request, $id)
    {
        $address = UserAddress::where('id', $id)?->first();
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
        ]);
        if ($validated['user_id'] == $address->user_id) {
            $address->delete();
            return new JsonResponse([
                'status' => true,
                'message' => 'Address Deleted',
            ]);
        }
        return new JsonResponse([
            'status' => false,
            'message' => "Can't Deleted Address",
        ]);
    }

    public function availablePincode(Request $request)
    {
        $validated = $request->validate([
            'pincode' => 'integer|required',
        ]);

        $pincode = Pincode::where('pincode', $validated['pincode'])?->first();
        if (isset($pincode)) {
            if ($pincode->usedBy != 0) {
                return new JsonResponse([
                    'status' => true,
                    'message' => "Pincode is occupied",
                ]);
            } else {
                return new JsonResponse([
                    'status' => false,
                    'message' => "Pincode is Available",
                ]);
            }
        } else {
            return new JsonResponse([
                'status' => false,
                'message' => "Pincode seems available, contact us.",
            ]);
        }
    }

    public function userBookingHistory(Request $d)
    {
        $validator = Validator::make($d->all(), [
            'user_id' => 'required|exists:users,user_id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $history = Booking::where('customer_id', $d->user_id)
                ->when(request('date'), function ($query, $date) {
                    $query->where(function ($query) use ($date) {
                        $query->whereDate('updated_at', '=', $date);
                    });
                })
                ->latest()->get();
            $book = [];
            foreach ($history as $h) {
                $booking_id = $h->booking_id;
                $book1 = DB::table("booking")->where('booking_id', $booking_id)->get(['customer_id', 'category', 'distance', 'pincode', "booking_id", "status", 'updated_at']);
                if ($book1[0]->status == 0) {
                    $bookstatus = "Searching For The Driver";
                }
                if ($book1[0]->status == 1) {
                    $bookstatus = "Driver Has Accepted the Order And He is On The Way";
                }
                if ($book1[0]->status == 2) {
                    $bookstatus = "Booking Has Been Completed";
                }
                if ($book1[0]->status == 3) {
                    $bookstatus = "Booking has Been Cancelled";
                }
                if ($book1[0]->status == 4) {
                    $bookstatus = "Your Ride Has Started";
                }
                $book1[0]->booking_status = $bookstatus;
                $user = User::where("user_id", $book1[0]->customer_id)->get(['name', 'email', 'phone', 'user_id', 'image']);
                $book2 = DB::table("booking_location_mapping")->where('booking_id', $booking_id)->get();
                $starting_location = DB::table("booking_locations")->where('location_id', $book2[0]->start_location_id)->get(['address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'landmark']);
                $endng_location = DB::table("booking_locations")->where('location_id', $book2[0]->end_location_id)->get(['address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'landmark']);
                $book3 = DB::table("booking_payment")->where('booking_id', $booking_id)->get(['total', 'base_price', 'tax', 'tax_split_1', 'tax_split_2', 'coupon_id', 'coupon_amount']);
                $book3[0]->roundofftotal = round($book3[0]->total);
                if ($book3[0]->coupon_id != null) {
                    $book3[0]->total = $book3[0]->total - $book3[0]->coupon_amount;
                }
                // $user[0], $book1[0], array("starting_location" => $starting_location[0]), array("ending_location" => $endng_location[0]),array("price" => $book3[0])
                array_push($book, array_merge(array("user" => $user[0]), array("bookingdetails" => $book1[0]), array("starting_location" => $starting_location[0]), array("ending_location" => $endng_location[0]), array("price" => $book3[0]), array("booked_at" => $book1[0]->updated_at), array("distance" => round($book1[0]->distance))));
            }
            return $this->sendResponse($book, 'Booking History of user');
        }
    }
}
