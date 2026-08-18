<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnduserBlockReason\EnduserBlockRequest;
use App\Http\Requests\EnduserBlockReason\EnduserUnblockRequest;
use App\Models\Admin;
use App\Models\Subscriber;
use App\Models\Enduser;
use App\Models\User;


use App\Models\Category;
use App\Models\Driver;
use App\Models\Pincode;
use Illuminate\Http\Request;
use App\Models\Drivernotify;
use Illuminate\Support\Facades\Hash;
use App\Models\Blocklist;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\BookingRating;
use App\Models\Checking;
use App\Models\Complaints;
use App\Models\Unblocklist;
use App\Models\Coupons;
use App\Models\Employee;
use App\Models\EnduserReason;
use App\Models\Enquiry;
use App\Models\Feedback;
use App\Models\NewsLetter;
use App\Models\Pricenotify;
use App\Models\SubBlock;
use App\Models\SubUnblock;
use App\Models\site;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:driver-list|driver-create|driver-edit|driver-destroy', ['only' => ['driver', 'show']]);
        $this->middleware('permission:driver-create', ['only' => ['createdriver', 'driverstore']]);
        $this->middleware('permission:driver-edit', ['only' => ['driveredit', 'driverupdate']]);
        $this->middleware('permission:driver-destroy', ['only' => ['destroy']]);

        $this->middleware('permission:category-list', ['only' => ['category']]);
        $this->middleware('permission:notification-list', ['only' => ['drivernotify']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function usedPincodes()
    {
        $pincodes = Schema::hasColumn('pincode', 'usedBy')
            ? Pincode::where('usedBy', '!=', 0)->latest()->get()
            : collect();
        return view('admin.category.pincode', ["pincodes" => $pincodes]);
    }

    public function appVerision(site $site)
    {
        //dd($site);
        return view('admin.site.site', ['site' => $site]);
    }

    public function updateAppVerision(Request $request, site $site)
    {
        $validated = $request->validate([
            'sitename' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address' => 'required',
            'driver_app' => 'required',
            'user_app' => 'required',
            'main_logo' => 'nullable',
            'sidebar_logo' => 'nullable',
            'sidebar_small_logo' => 'nullable',
            'favicon' => 'nullable'
        ]);
        if ($request->hasFile('main_logo')) {
            $imageName = uniqid() . "." . $request->main_logo->extension();
            $request->main_logo->move(public_path('site'), $imageName);
            $validated['main_logo'] = $imageName;
        }
        if ($request->hasFile('sidebar_logo')) {
            $imageName = uniqid() . "." . $request->sidebar_logo->extension();
            $request->sidebar_logo->move(public_path('site'), $imageName);
            $validated['sidebar_logo'] = $imageName;
        }
        if ($request->hasFile('sidebar_small_logo')) {
            $imageName = uniqid() . "." . $request->sidebar_small_logo->extension();
            $request->sidebar_small_logo->move(public_path('site'), $imageName);
            $validated['sidebar_small_logo'] = $imageName;
        }
        if ($request->hasFile('favicon')) {
            $imageName = uniqid() . "." . $request->favicon->extension();
            $request->favicon->move(public_path('site'), $imageName);
            $validated['favicon'] = $imageName;
        }
        $site->update($validated);
        return back()->with('success', "Updated Successfully");
    }

    public function index()
    {

        $subscriber = Subscriber::get();
        $subscriptionAmount = $subscriber;
        // dd($subscriptionAmount);

        $subscriber = $subscriber->count();
        $enduser = Enduser::where('is_driver', 0)->count();
        $category = Category::all()->count();
        $id = Auth::id();

        $checking = Checking::where('admin_id', $id)->latest()->first();


        return view('admin.dashboard', compact('enduser', 'subscriber', 'category', 'checking'));
    }
    // public function index()
    // {
    //     $subscriberCount = Subscriber::count();
    //     $enduserCount = Enduser::count();
    //     $categoryCount = Category::count();
    //     $id = Auth::id();
    //     $checking = Checking::where('admin_id', $id)->latest()->first();

    //     return view('admin.dashboard', compact('subscriberCount', 'enduserCount', 'categoryCount', 'checking'));
    // }
    //     public function chart()
    // {

    // }



    //Dashboard
    public function dashboardBackup()
    {
        $subscriber = Subscriber::get();
        $subscriptionAmount = $subscriber->sum('platform_fee');
        // dd($subscriptionAmount);
        $subscriber = $subscriber->count();
        $enduser = Enduser::where('is_driver', 0)->count();
        $driversCount = Driver::all()->count();
        $category = Category::all()->count();
        $complaintCount = Complaints::all()->count();
        $enquiryCount = Enquiry::all()->count();
        $employeeCount = Employee::all()->count();
        $feedbackCount = Feedback::all()->count();
        $newsletterCount = NewsLetter::all()->count();
        $solvedComplaints = Complaints::where('solved_by', '!=', NULL)->get()->count();

        $id = Auth::id();
        // dd($id);
        $checking = Checking::where('admin_id', $id)->latest()->first();
        $bookingCount = Booking::all()->count();
        $bookingPrice = Booking::with('bookingPayment')->get();
        $totalBookingAmount = $bookingPrice->sum(function ($booking) {
            return $booking->bookingPayment->sum('total');
        });
        $bikeTaxi = Booking::where('category', 1)->with('bookingPayment')->get();
        $bikeTaxiTotal = $bikeTaxi->sum(function ($booking) {
            return $booking->bookingPayment->sum('total');
        });
        $pickup = Booking::where('category', 2)->with('bookingPayment')->get();
        $pickupTotal = $pickup->sum(function ($booking) {
            return $booking->bookingPayment->sum('total');
        });
        $dropAndDelivery = Booking::where('category', 3)->with('bookingPayment')->get();
        $dropAndDeliveryTotal = $dropAndDelivery->sum(function ($booking) {
            return $booking->bookingPayment->sum('total');
        });
        $totalServiceAmount = $bikeTaxiTotal + $pickupTotal + $dropAndDeliveryTotal;
        $inactiveSubcribers = Subscriber::where('status', 0)->get()->count();

        $inactivesubamount = Subscriber::where('status', 0)->get()->sum('platform_fee');
        $completeRides = Booking::where('status', 2)->get()->count();
        $cancelledRides = Booking::where('status', 3)->get()->count();
        $inprocessRides = Booking::where('status', 1)->get()->count();
        $blockedRiders = Driver::where('status', 2)->get()->count();
        $blockedSubscribers = Subscriber::where('blockedstatus', 0)->get()->count();
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $pincodeSales = Pincode::where('usedBy', '!=', '0')
            ->where('updated_at', '>=', $thirtyDaysAgo)
            ->get();
        $salesCount = $pincodeSales->count();
        $booking = Booking::with('bookingPayment')->get();
        $bookingCost = $booking->sum(function ($booking) {
            return $booking->bookingPayment->sum('total');
        });
        $books = Booking::latest()->get();
        $totalBookingCostWithServiceCost = 0;
        foreach ($books as $book) {
            //dd($book);
            $pincodeRecord = Pincode::where('pincode', $book->pincode)->first();
            if ($pincodeRecord) {
                $subId = $pincodeRecord->usedBy;
                $subscriberrr = Subscriber::where('id', $subId)->first();
                if ($subscriberrr) {
                    if ($book->category == 1) {
                        $totalBookingCostWithServiceCost += $subscriberrr?->biketaxi_price;
                        $totalBookingCostWithServiceCost += $book?->bookingPayment[0]?->total;
                    } elseif ($book->category == 2) {
                        $totalBookingCostWithServiceCost += $subscriberrr?->pickup_price;
                        $totalBookingCostWithServiceCost += $book?->bookingPayment[0]?->total;
                    } else {
                        $totalBookingCostWithServiceCost += $subscriberrr?->buy_price;
                        $totalBookingCostWithServiceCost += $book?->bookingPayment[0]?->total;
                    }
                }
            }
        }

        //dd($totalBookingCostWithServiceCost);
        $buttonColors = [
            'btn-secondary',
            'btn-dark',
            'btn-primary',
            'btn-success',
            'btn-warning',
            'btn-danger',
        ];

        $counts = [];

        for ($days = 5; $days >= 1; $days--) {
            $count = Subscriber::whereDate('expiryDate', '=', now()->addDays($days)->toDateString())
                ->count();

            $counts[] = [
                'days_left' => $days . ' ' . 'days left',
                'count' => $count,
                'color' => $buttonColors[$days - 1], // Adjust index since array is zero-based
            ];
        }
        $completedBookingId = Booking::where('status', 2)->get()->pluck('booking_id');
        $completedBookingTotal = BookingPayment::whereIn('booking_id', $completedBookingId)->sum('total');
        $cancelledBookingId = Booking::where('status', 3)->get()->pluck('booking_id');
        $cancelledBookingTotal = BookingPayment::whereIn('booking_id', $cancelledBookingId)->sum('total');
        $inprocessBookingId = Booking::where('status', 1)->get()->pluck('booking_id');
        $inprocessBookingTotal = BookingPayment::whereIn('booking_id', $inprocessBookingId)->sum('total');
        // dd($completedBookingTotal);

        $expiredSubscribers = Subscriber::whereDate('expiryDate', '<', now()->format('Y-m-d'))->get();
        $expiredSubscriberCount = $expiredSubscribers->count();
        $expiredSubscriptionPrice = $expiredSubscribers->sum('subscription_price');

        // dd($expiredSubscriberCount, $expiredSubscriptionPrice);
        return view('admin.dashboard', compact('expiredSubscriberCount', 'expiredSubscriptionPrice', 'bookingCost', 'cancelledBookingTotal', 'inprocessBookingTotal', 'inactiveSubcribers', 'totalServiceAmount', 'inactivesubamount', 'enduser', 'subscriber', 'category', 'complaintCount', 'driversCount', 'bookingCount', 'checking', 'subscriptionAmount', 'solvedComplaints', 'totalBookingAmount', 'bikeTaxiTotal', 'pickupTotal', 'dropAndDeliveryTotal', 'enquiryCount', 'employeeCount', 'newsletterCount', 'feedbackCount', 'inprocessRides', 'cancelledRides', 'completeRides', 'blockedSubscribers', 'blockedRiders', 'salesCount', 'totalBookingCostWithServiceCost', 'counts', 'completedBookingTotal'));
    }

    public function dashboard()
    {
        $subscriptionAmount = Subscriber::selectRaw('SUM(CAST(platform_fee AS DECIMAL(10,2))) as total')->value('total') ?? 0;
        $subscriberCount = Subscriber::count();
        $enduser = \Illuminate\Support\Facades\Schema::hasColumn('users', 'is_driver') ? Enduser::where('is_driver', 0)->count() : Enduser::count();
        $driversCount = Driver::count();
        $category = Category::count();
        $complaintCount = Complaints::count();
        $enquiryCount = Enquiry::count();
        $employeeCount = Employee::count();
        $feedbackCount = Feedback::count();
        $newsletterCount = NewsLetter::count();
        $solvedComplaints = \Illuminate\Support\Facades\Schema::hasColumn('complaints', 'solved_by') ? Complaints::whereNotNull('solved_by')->count() : 0;

        $id = Auth::id();
        $checking = Checking::where('admin_id', $id)->latest()->first();
        $bookingCount = Booking::count();
        $totalBookingAmount = $this->bookingPaymentTotal();
        $bikeTaxiTotal = $this->bookingPaymentTotal(['category' => 1]);
        $pickupTotal = $this->bookingPaymentTotal(['category' => 2]);
        $dropAndDeliveryTotal = $this->bookingPaymentTotal(['category' => 3]);
        $autoTotal = $this->bookingPaymentTotal(['category' => 4]);
        $cabTotal = $this->bookingPaymentTotal(['category' => 5]);

        $totalServiceAmount = $bikeTaxiTotal + $pickupTotal + $dropAndDeliveryTotal + $autoTotal + $cabTotal;
        $inactiveSubscribers = Subscriber::where('status', 0)->count();
        $inactiveSubscriptionAmount = Subscriber::where('status', 0)->selectRaw('SUM(CAST(platform_fee AS DECIMAL(10,2))) as total')->value('total') ?? 0;
        $completeRides = Booking::where('status', 2)->count();
        $cancelledRides = Booking::where('status', 3)->count();
        $inprocessRides = Booking::where('status', 1)->count();
        $blockedRiders = Driver::where('status', 2)->count();
        $blockedSubscribers = Subscriber::where('blockedstatus', 0)->count();
        $thirtyDaysAgo = now()->subDays(30);
        $pincodeSales = \Illuminate\Support\Facades\Schema::hasColumn('pincode', 'usedBy')
            ? Pincode::where('usedBy', '!=', '0')->where('updated_at', '>=', $thirtyDaysAgo)->count()
            : 0;
        $salesCount = $pincodeSales;
        $bookingCost = $totalBookingAmount;
        $totalBookingCostWithServiceCost = $this->assignedPincodeBookingPaymentTotal();

        $buttonColors = ['btn-secondary', 'btn-dark', 'btn-primary', 'btn-success', 'btn-warning', 'btn-danger'];
        $counts = [];

        for ($days = 5; $days >= 1; $days--) {
            $count = Subscriber::whereDate('expiryDate', '=', now()->addDays($days)->toDateString())
                ->count();

            $counts[] = [
                'days_left' => $days . ' ' . 'days left',
                'count' => $count,
                'color' => $buttonColors[$days - 1],
            ];
        }

        $completedBookingTotal = $this->bookingPaymentTotal(['status' => 2]);
        $cancelledBookingTotal = $this->bookingPaymentTotal(['status' => 3]);
        $inprocessBookingTotal = $this->bookingPaymentTotal(['status' => 1]);

        $expiredSubscriberQuery = Subscriber::whereDate('expiryDate', '<', now()->format('Y-m-d'));
        $expiredSubscriberCount = (clone $expiredSubscriberQuery)->count();
        $expiredSubscriptionPrice = (clone $expiredSubscriberQuery)->selectRaw('SUM(CAST(platform_fee AS DECIMAL(10,2))) as total')->value('total') ?? 0;

        return view('admin.dashboard', compact(
            'expiredSubscriberCount',
            'expiredSubscriptionPrice',
            'bookingCost',
            'cancelledBookingTotal',
            'inprocessBookingTotal',
            'inactiveSubscribers',
            'totalServiceAmount',
            'inactiveSubscriptionAmount',
            'enduser',
            'subscriberCount',
            'category',
            'complaintCount',
            'driversCount',
            'bookingCount',
            'checking',
            'subscriptionAmount',
            'solvedComplaints',
            'totalBookingAmount',
            'bikeTaxiTotal',
            'pickupTotal',
            'autoTotal',
            'cabTotal',
            'dropAndDeliveryTotal',
            'enquiryCount',
            'employeeCount',
            'newsletterCount',
            'feedbackCount',
            'inprocessRides',
            'cancelledRides',
            'completeRides',
            'blockedSubscribers',
            'blockedRiders',
            'salesCount',
            'totalBookingCostWithServiceCost',
            'counts',
            'completedBookingTotal'
        ));
    }

    private function bookingPaymentTotal(array $bookingFilters = []): float
    {
        $query = BookingPayment::query()
            ->join('booking', 'booking.booking_id', '=', 'booking_payment.booking_id');

        foreach ($bookingFilters as $column => $value) {
            $query->where('booking.' . $column, $value);
        }

        return (float) $query
            ->selectRaw('COALESCE(SUM(booking_payment.total - CASE WHEN booking_payment.coupon_id IS NOT NULL THEN COALESCE(booking_payment.coupon_amount, 0) ELSE 0 END), 0) AS aggregate')
            ->value('aggregate');
    }

    private function assignedPincodeBookingPaymentTotal(): float
    {
        if (!\Illuminate\Support\Facades\Schema::hasColumn('pincode', 'usedBy')) {
            return (float) BookingPayment::query()
                ->join('booking', 'booking.booking_id', '=', 'booking_payment.booking_id')
                ->selectRaw('COALESCE(SUM(booking_payment.total - CASE WHEN booking_payment.coupon_id IS NOT NULL THEN COALESCE(booking_payment.coupon_amount, 0) ELSE 0 END), 0) AS aggregate')
                ->value('aggregate');
        }

        return (float) BookingPayment::query()
            ->join('booking', 'booking.booking_id', '=', 'booking_payment.booking_id')
            ->join('pincode', 'pincode.pincode', '=', 'booking.pincode')
            ->join('subscriber', 'subscriber.id', '=', 'pincode.usedBy')
            ->selectRaw('COALESCE(SUM(booking_payment.total - CASE WHEN booking_payment.coupon_id IS NOT NULL THEN COALESCE(booking_payment.coupon_amount, 0) ELSE 0 END), 0) AS aggregate')
            ->value('aggregate');
    }

    private function subscriberSubscriptionPriceTotal(array $filters = []): float
    {
        if (!Schema::hasColumn('subscriber', 'subscription_price')) {
            return 0.0;
        }

        $query = Subscriber::query();

        foreach ($filters as $filter) {
            if (count($filter) === 2) {
                $query->where($filter[0], $filter[1]);
                continue;
            }

            if (count($filter) === 3) {
                $query->where($filter[0], $filter[1], $filter[2]);
            }
        }

        return (float) $query->sum('subscription_price');
    }

    //End user
    public function enduserbackup()
    {
        $enduser = Enduser::where('is_driver', 0)->with('enduserreason')->latest()->get();
        // dd($enduser);
        return view('admin.enduser.index', compact('enduser'));
    }

    public function enduser()
    {
        $endusers = Enduser::where('is_driver', 0)
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('user_id', 'LIKE', "$search%")
                        ->orWhere('name', 'LIKE', "$search%")
                        ->orWhere('email', 'LIKE', "$search%")
                        ->orWhere('phone', 'LIKE', "$search%");
                });
            })
            ->with('enduserreason')
            ->withCount('referrals')
            ->latest()
            ->paginate(16)
            ->withQueryString();
        // dd($enduser);
        return view('admin.enduser.index', ['endusers' => $endusers]);
    }

    public function blockEndUser(EnduserBlockRequest $request, $id)
    {
        //   dd($request->validated());
        // dd($id);
        $endUser = Enduser::find($id);
        if ($endUser) {
            //dd($endUser?->user_id);
            $endUser->blockedstatus = 0;
            $endUser->save();
            EnduserReason::create($request->validated());
            $bookings = Booking::where('customer_id', $endUser?->user_id)
                ->where('status', 0)
                ->orWhere('status', 1)
                ->orWhere('status', 4)
                ->latest()
                ->get();
            //dd($booking);
            //$booking->status = 3;
            foreach ($bookings as $booking) {
                $booking->update([
                    'status' => 2,
                ]);
            }
            $user = Enduser::find($id);
            if (isset($user)) {
                $title = "Account Banned!!!";
                $content = "Your account has been banned due to a violation of our terms of service. If you believe this was a mistake, please contact support for assistance.";
                $notification = $this->sendNotification($user, $title, $content);
            }
            return redirect()->back()->with('success', 'End user blocked successfully.');
        } else {
            return redirect()->back()->with('error', 'End user not found.');
        }
    }

    public function unblockEndUser(EnduserUnblockRequest $request, $id)
    {
        // dd($id);
        // dd($request->validated());
        $endUser = Enduser::find($id);
        if ($endUser) {
            $endUser->blockedstatus = 1;
            $endUser->save();
            EnduserReason::create($request->validated());
            return redirect()->back()->with('success', 'End user unblocked successfully.');
        } else {
            return redirect()->back()->with('error', 'End user not found.');
        }
    }

    public function sendNotification($user, $title, $content)
    {
        $fcm_token = site::where('id', 1)?->first();
        if (isset($user)) {
            if ($user->is_driver == 1) {
                $token = $user->device_token;
                $fcm_token = $fcm_token->driverToken;
                $url = "https://fcm.googleapis.com/v1/projects/donkey-driver/messages:send";
            } else {
                $token = $user->device_token;
                $fcm_token = $fcm_token->userToken;
                $url = "https://fcm.googleapis.com/v1/projects/doncky-user/messages:send";
            }
            // Compile headers in one variable
            $headers = array(
                'Authorization: ' . $fcm_token,
                'Content-Type: application/json'
            );

            // Notification payload
            $notifData = [
                'title' => $title,
                'body' => $content,
            ];

            // Data payload (extra data)
            $dataPayload = [
                'title' => $title,
                'body' => $content,
                'story_id' => "story_12345"
            ];

            $apiBody = [
                'message' => [
                    'token' => $token, // Target device token
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
            return $result;
        }
    }

    //Category View
    public function category()
    {
        $category = Category::all();
        return view('admin.category.index', compact('category'));
    }

    public function categoryStatus(Request $request)
    {

        $data = Category::findOrFail($request->id);
        $data->status = $request->status;
        $data->save();



        return response()->json(['success' => 'Status updated successfully.']);
    }
    public function driver()
    {
        $pincode = Pincode::all();
        $driver = Driver::join('subscriber', 'driver.subscriberId', '=', 'subscriber.id')
            ->latest()
            ->select('driver.*', 'subscriber.name as subscribername')
            ->get();
        $driverUserIds = $driver->pluck('userid')->filter()->unique();
        $driverUsers = User::whereIn('id', $driverUserIds)->get()->keyBy('id');
        $driverRatings = BookingRating::whereIn('driver_id', $driverUserIds)
            ->selectRaw('driver_id, ROUND(AVG(rating), 1) as average_rating')
            ->groupBy('driver_id')
            ->pluck('average_rating', 'driver_id');
        //dd($driver);
        return view('admin.driver.index', compact('driver', 'pincode', 'driverUsers', 'driverRatings'));
    }
    public function createdriver()
    {

        $subscriber = Subscriber::all();
        $pincode = Pincode::all();
        $languages = [
            'Assamese',
            'Bengali',
            'Bodo',
            'Dogri',
            'English',
            'Gujarati',
            'Hindi',
            'Kannada',
            'Kashmiri',
            'Konkani',
            'Maithili',
            'Malayalam',
            'Marathi',
            'Meitei',
            'Nepali',
            'Odia',
            'Punjabi',
            'Sanskrit',
            'Santali',
            'Sindhi',
            'Tamil',
            'Telugu',
            'Urdu',
        ];
        return view('admin.driver.create', compact('pincode', 'subscriber', 'languages'));
    }

    public function driverstore(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'name' => 'required',
            'location' => 'nullable',
            'subscriber' => 'required',
            'email' => 'nullable|email|unique:users,email|unique:driver,email',
            'mobile' => ['required', 'max:12', 'unique:users,phone', 'unique:driver,mobile'],
            'pincode' => 'required',
            'language' => 'required',
            'password' => 'required',
            'dob' => 'nullable',
            'gender' => 'required',
            'aadharNo' => 'required|numeric|unique:driver,aadharNo',
            'aadharFrontImage' => 'required|mimes:pdf',
            'aadharBackImage' => 'required|mimes:pdf',
            'rcbook' => 'required|mimes:pdf',
            // 'insurance' => 'required|mimes:pdf',
            'bike' => 'nullable|mimes:pdf',
            'drivingLicence' => 'required|mimes:pdf',
            'vehicleNo' => 'required',
            'vehicleModelNo' => 'required',
            'licenceexpiry' => 'nullable',
            'customerdocument' => 'nullable|mimes:pdf',
            'profile' => 'nullable',
            'type' => ['required', 'array'],
            'type.*' => ['required', 'in:1,2,3'],
            'bankacno' => 'nullable',
            'description' => 'nullable',
            'ifsccode' => 'nullable'
        ]);

        $selectedTypes = $request->input('type', []);
        if (!is_array($selectedTypes)) {
            $selectedTypes = [$selectedTypes];
        }
        $typeString = implode(',', $selectedTypes);

        $input = $request->all();
        // dd($input);
        $input['password'] = bcrypt($input['password']);
        $input['user_id'] = 'DK-' . uniqid();
        $input['is_driver'] = 1;
        $input['dob'] = $request->get('dob');
        $input['gender'] = $request->get('gender');
        $input['otp'] = rand(1000, 9999);
        if ($request->hasFile('profile')) {
            $imageName = uniqid() . "." . $request->profile->extension();
            $request->profile->move(public_path('subscriber/driver/profile'), $imageName);
            $input['image'] = $imageName;
        }
        $user = User::create($input);
        $userid = $user->id;
        $pincode = array();
        $pincode = json_encode($request->pincode);
        $language = array();
        $language = $request->language;
        $languageString = implode(',', $language);

        $driver = new driver();
        $driver->name = $request->get('name');
        $driver->location = $request->get('location');
        $driver->licenceexpiry = $request->get('licenceexpiry');
        $driver->userid = $userid;
        $driver->email = $request->get('email');
        $driver->mobile = $request->get('mobile');
        $driver->pincode = $pincode;
        $driver->language = $languageString;
        $driver->source = $request->get('password');
        $driver->password = Hash::make($request->get('password'));
        $driver->aadharNo = $request->get('aadharNo');
        $driver->description = $request->get('description');
        $driver->bankacno = $request->get('bankacno');
        $driver->ifsccode = $request->get('ifsccode');
        if ($request->hasFile('customerdocument')) {
            $customerdocument = time() . '.' . $request->customerdocument->extension();
            $request->customerdocument->move(public_path('subscriber/driver/document'), $customerdocument);
            $driver->customerdocument = $customerdocument;
        }
        $aadharFrontImage = time() . '.' . $request->aadharFrontImage->extension();
        $request->aadharFrontImage->move(public_path('subscriber/driver/aadhar'), $aadharFrontImage);
        $driver->aadharFrontImage = $aadharFrontImage;

        $aadharBackImage = time() . '.' . $request->aadharBackImage->extension();
        $request->aadharBackImage->move(public_path('subscriber/driver/aadhar/back'), $aadharBackImage);
        $driver->aadharBackImage = $aadharBackImage;

        $rcbook = time() . '.' . $request->rcbook->extension();
        $request->rcbook->move(public_path('subscriber/driver/rcbook'), $rcbook);
        $driver->rcbook = $rcbook;

        $drivingLicence = time() . '.' . $request->drivingLicence->extension();
        $request->drivingLicence->move(public_path('subscriber/driver/drivingLicence'), $drivingLicence);
        $driver->drivingLicence = $drivingLicence;

        //  $insurance = time() . '.' . $request->insurance->extension();
        //  $request->insurance->move(public_path('subscriber/driver/insurance'), $insurance);
        //  $driver->insurance = $insurance;

        if ($request->hasFile('bike')) {
            $bike = time() . '.' . $request->bike->extension();
            $request->bike->move(public_path('subscriber/driver/bike'), $bike);
            $driver->bike = $bike;
        }

        $driver->vehicleNo = $request->get('vehicleNo');
        $driver->vehicleModelNo = $request->get('vehicleModelNo');
        $driver->subscriberId = $request->get('subscriber');
        $driver->type = $typeString;
        $driver->save();
        return redirect()->route('drivers')->with('success', 'Rider added successfully.');
    }

    public function driverstorebackup(Request $request)
    {
        //dd($request);
        $this->validate($request, [
            'name' => 'required',
            'location' => 'required',
            'subscriber' => 'required',
            'email' => 'required|email|unique:users,email|unique:driver,email',
            'mobile' => ['required', 'max:12', 'unique:users,phone', 'unique:driver,mobile'],
            'pincode' => 'required',
            'language' => 'required',
            'password' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'aadharNo' => 'required|numeric|unique:driver,aadharNo',
            'aadharFrontImage' => 'required|mimes:pdf',
            'aadharBackImage' => 'required|mimes:pdf',
            'rcbook' => 'required|mimes:pdf',
            'insurance' => 'required|mimes:pdf',
            'bike' => 'required|mimes:pdf',
            'drivingLicence' => 'required|mimes:pdf',
            'vehicleNo' => 'required',
            'vehicleModelNo' => 'required',
            'licenceexpiry' => 'required',
            'customerdocument' => 'mimes:pdf',
            'profile' => 'nullable',
            'type' => 'required'
        ]);

        $input = $request->all();
        // dd($input);
        $input['password'] = bcrypt($input['password']);
        $input['user_id'] = 'DK-' . uniqid();
        $input['is_driver'] = 1;
        $input['dob'] = $request->get('dob');
        $input['gender'] = $request->get('gender');
        $input['otp'] = rand(1000, 9999);
        if ($request->hasFile('profile')) {
            $imageName = uniqid() . "." . $request->profile->extension();
            $request->profile->move(public_path('subscriber/driver/profile'), $imageName);
            $input['image'] = $imageName;
        }
        $user = User::create($input);
        $userid = $user->id;
        $pincode = array();
        $pincode = json_encode($request->pincode);
        $language = array();
        $language = $request->language;
        $languageString = implode(',', $language);

        $driver = new driver();
        $driver->name = $request->get('name');
        $driver->location = $request->get('location');
        $driver->licenceexpiry = $request->get('licenceexpiry');
        $driver->userid = $userid;
        $driver->email = $request->get('email');
        $driver->mobile = $request->get('mobile');
        $driver->pincode = $pincode;
        $driver->language = $languageString;
        $driver->source = $request->get('password');
        $driver->password = Hash::make($request->get('password'));
        $driver->aadharNo = $request->get('aadharNo');
        $driver->description = $request->get('description');
        $driver->bankacno = $request->get('bankacno');
        $driver->ifsccode = $request->get('ifsccode');
        if ($request->hasFile('customerdocument')) {
            $customerdocument = time() . '.' . $request->customerdocument->extension();
            $request->customerdocument->move(public_path('subscriber/driver/document'), $customerdocument);
            $driver->customerdocument = $customerdocument;
        }
        $aadharFrontImage = time() . '.' . $request->aadharFrontImage->extension();
        $request->aadharFrontImage->move(public_path('subscriber/driver/aadhar'), $aadharFrontImage);
        $driver->aadharFrontImage = $aadharFrontImage;

        $aadharBackImage = time() . '.' . $request->aadharBackImage->extension();
        $request->aadharBackImage->move(public_path('subscriber/driver/aadhar/back'), $aadharBackImage);
        $driver->aadharBackImage = $aadharBackImage;

        $rcbook = time() . '.' . $request->rcbook->extension();
        $request->rcbook->move(public_path('subscriber/driver/rcbook'), $rcbook);
        $driver->rcbook = $rcbook;

        $drivingLicence = time() . '.' . $request->drivingLicence->extension();
        $request->drivingLicence->move(public_path('subscriber/driver/drivingLicence'), $drivingLicence);
        $driver->drivingLicence = $drivingLicence;

        $insurance = time() . '.' . $request->insurance->extension();
        $request->insurance->move(public_path('subscriber/driver/insurance'), $insurance);
        $driver->insurance = $insurance;

        $bike = time() . '.' . $request->bike->extension();
        $request->bike->move(public_path('subscriber/driver/bike'), $bike);
        $driver->bike = $bike;

        $driver->vehicleNo = $request->get('vehicleNo');
        $driver->vehicleModelNo = $request->get('vehicleModelNo');
        $driver->subscriberId = $request->get('subscriber');
        $driver->type = $request->get('type');
        $driver->save();
        return redirect()->route('drivers')->with('success', 'Driver added!');
    }
    public function show($id)
    {
        $driver = Driver::find($id);
        $user = User::where('id', $driver->userid)->get();
        $pin = json_decode($driver->pincode);
        $pincode = Pincode::whereIn('id', $pin)->get();
        $subscriber = Subscriber::get();
        $admins = Admin::all();
        $employees = Employee::all();
        //dd($pincode);
        $blocklist = Blocklist::join('driver', 'driver.id', '=', 'blocklist.blockedId')->join('subscriber', 'subscriber.id', '=', 'blocklist.blockedBy')->where('table', 'driver')->paginate(16, array('blocklist.*', 'driver.name as drivername', 'subscriber.name as subscribername', 'driver.status as driverstatus'));
        $notification = Drivernotify::where([['modifiedId', $id]])->get();
        return view('admin.driver.show', compact('user', 'driver', 'pincode', 'blocklist', 'notification', 'subscriber', 'admins', 'employees'));
    }


    public function driverActivate(Request $request)
    {

        $data = Driver::findOrFail($request->id);
        $status = (int) $request->status;
        $data->status = $status;
        $data->save();

        if ($data->userid) {
            User::where('id', $data->userid)->update([
                'is_live' => $status === 1 ? 1 : 0,
            ]);
        }


        return response()->json(['success' => 'Status updated successfully.']);
    }
    public function driveredit($id)
    {
        $driver = Driver::find($id);
        $user = User::where('id', $driver->userid)->get();

        $pincode = Pincode::all();
        $languages = [
            'Assamese',
            'Bengali',
            'Bodo',
            'Dogri',
            'English',
            'Gujarati',
            'Hindi',
            'Kannada',
            'Kashmiri',
            'Konkani',
            'Maithili',
            'Malayalam',
            'Marathi',
            'Meitei',
            'Nepali',
            'Odia',
            'Punjabi',
            'Sanskrit',
            'Santali',
            'Sindhi',
            'Tamil',
            'Telugu',
            'Urdu',
        ];
        $languageString = $driver->language;

        // Convert the string into an array
        $languageArray = explode(',', $languageString);
        //dd($pincode);
        return view('admin.driver.edit', compact('driver', 'pincode', 'user', 'languages', 'languageArray'));
    }

    public function driverupdate(Request $request, $id)
    {
        $driver = Driver::find($id);
        // dd($request->all());
        $this->validate($request, [
            'name' => 'required',
            'location' => 'nullable',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($driver->userid, 'id'),
                Rule::unique('driver', 'email')->ignore($driver->id, 'id'),
            ],
            'mobile' => [
                'required',
                'max:12',
                Rule::unique('users', 'phone')->ignore($driver->userid, 'id'),
                Rule::unique('driver', 'mobile')->ignore($driver->id, 'id'),
            ],
            'pincode' => 'required',
            'language' => 'required',
            'password' => 'nullable',
            'dob' => 'nullable',

            'gender' => 'required',

            'aadharNo' => 'required|numeric',

            'vehicleNo' => 'required',
            'vehicleModelNo' => 'required',
            'licenceexpiry' => 'nullable',
            'profile' => 'nullable',
            'type' => ['required', 'array'],
            'type.*' => ['required', 'in:1,2,3'],
            'description' => 'nullable',
            'bankacno' => 'nullable',
            'ifsccode' => 'nullable',
            'customerdocument' => 'nullable',
        ]);
        // dd($request->dob);
        $pincode = array();
        $pincode = json_encode($request->pincode);

        $language = $request->language;
        $languageString = implode(',', $language);

        $driver = Driver::findorFail($id);
        $driver->name = $request->get('name');
        $driver->location = $request->get('location');
        $driver->email = $request->get('email');
        $driver->mobile = $request->get('mobile');
        $driver->pincode = $pincode;
        $driver->language = $languageString;
        // $driver->source = $request->get('password');
        $driver->licenceexpiry = $request->get('licenceexpiry');
        // $driver->password = Hash::make($request->get('password'));
        $driver->aadharNo = $request->get('aadharNo');
        $driver->description = $request->get('description');
        $driver->bankacno = $request->get('bankacno');
        $driver->ifsccode = $request->get('ifsccode');
        $selectedTypes = $request->input('type', []);
        if (!is_array($selectedTypes)) {
            $selectedTypes = [$selectedTypes];
        }
        $driver->type = implode(',', $selectedTypes);
        if ($request->get('password') != "") {
            $driver->password = Hash::make($request->get('password'));
        }
        if ($request->hasFile('aadharFrontImage')) {
            $aadharFrontImage = time() . '.' . $request->aadharFrontImage->extension();
            $request->aadharFrontImage->move(public_path('subscriber/driver/aadhar'), $aadharFrontImage);
            $driver->aadharFrontImage = $aadharFrontImage;
        }
        if ($request->hasFile('aadharBackImage')) {
            $aadharBackImage = time() . '.' . $request->aadharBackImage->extension();
            $request->aadharBackImage->move(public_path('subscriber/driver/aadhar/back'), $aadharBackImage);
            $driver->aadharBackImage = $aadharBackImage;
        }
        if ($request->hasFile('customerdocument')) {
            $customerdocument = time() . '.' . $request->customerdocument->extension();
            $request->customerdocument->move(public_path('subscriber/driver/document'), $customerdocument);
            $driver->customerdocument = $customerdocument;
        }
        if ($request->hasFile('rcbook')) {
            $rcbook = time() . '.' . $request->rcbook->extension();
            $request->rcbook->move(public_path('subscriber/driver/rcbook'), $rcbook);
            $driver->rcbook = $rcbook;
        }
        if ($request->hasFile('drivingLicence')) {
            $drivingLicence = time() . '.' . $request->drivingLicence->extension();
            $request->drivingLicence->move(public_path('subscriber/driver/drivingLicence'), $drivingLicence);
            $driver->drivingLicence = $drivingLicence;
        }
        // if ($request->hasFile('insurance')) {
        //     $insurance = time() . '.' . $request->insurance->extension();
        //     $request->insurance->move(public_path('subscriber/driver/insurance'), $insurance);
        //     $driver->insurance = $insurance;
        // }
        if ($request->hasFile('bike')) {
            $bike = time() . '.' . $request->bike->extension();
            $request->bike->move(public_path('subscriber/driver/bike'), $bike);
            $driver->bike = $bike;
        }
        $driver->vehicleNo = $request->get('vehicleNo');
        $driver->vehicleModelNo = $request->get('vehicleModelNo');
        $changes = $driver->getDirty();
        $driver->update();
        $data = json_encode($changes, true);
        $notify = new Drivernotify();
        $notify->datas = $data;
        $notify->modifiedBy = implode(",", array('Admin', Auth::id()));
        $notify->modifiedId = $id;
        $notify->message = $request->get('comments');
        $notify->save();
        //dd($changes);
        $driver = Driver::find($id);
        $u = User::where('id', $driver->userid)->update([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'phone' => $request->get('mobile'),
            'dob' => $request->get('dob'),
            'gender' => $request->get('gender'),
        ]);
        if ($request->get('password') != "") {
            $hashedPassword = Hash::make($request->get('password'));
            User::where('id', $driver->userid)->update([
                'password' => $hashedPassword,
            ]);
        }
        if ($request->hasFile('profile')) {
            $imageName = uniqid() . "." . $request->profile->extension();
            $request->profile->move(public_path('subscriber/driver/profile/'), $imageName);
            User::where('id', $driver->userid)->update([
                'image' => $imageName
            ]);
        }
        // $u->dop = $request->get('dob');
        // $u->gender = $request->get('gender');
        return redirect()->route('drivers')->with('success', 'Rider updated successfully.');
    }

    public function driverupdatebackup(Request $request, $id)
    {
        $driver = Driver::find($id);
        $this->validate($request, [
            'name' => 'required',
            'location' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($driver->userid, 'id'),
                Rule::unique('driver', 'email')->ignore($driver->id, 'id'),
            ],
            'mobile' => [
                'required',
                'max:12',
                Rule::unique('users', 'phone')->ignore($driver->userid, 'id'),
                Rule::unique('driver', 'mobile')->ignore($driver->id, 'id'),
            ],
            'pincode' => 'required',
            'language' => 'required',
            'password' => 'nullable',
            'dob' => 'required',

            'gender' => 'required',

            'aadharNo' => 'required|numeric',

            'vehicleNo' => 'required',
            'vehicleModelNo' => 'required',
            'licenceexpiry' => 'required',
            'profile' => 'nullable',
            'type' => 'required',
        ]);
        // dd($request->dob);
        $pincode = array();
        $pincode = json_encode($request->pincode);

        $language = $request->language;
        $languageString = implode(',', $language);

        $driver = Driver::findorFail($id);
        $driver->name = $request->get('name');
        $driver->location = $request->get('location');
        $driver->email = $request->get('email');
        $driver->mobile = $request->get('mobile');
        $driver->pincode = $pincode;
        $driver->language = $languageString;
        // $driver->source = $request->get('password');
        $driver->licenceexpiry = $request->get('licenceexpiry');
        // $driver->password = Hash::make($request->get('password'));
        $driver->aadharNo = $request->get('aadharNo');
        $driver->description = $request->get('description');
        $driver->bankacno = $request->get('bankacno');
        $driver->ifsccode = $request->get('ifsccode');
        $driver->type = $request->get('type');
        if ($request->get('password') != "") {
            $driver->password = Hash::make($request->get('password'));
        }
        if ($request->hasFile('aadharFrontImage')) {
            $aadharFrontImage = time() . '.' . $request->aadharFrontImage->extension();
            $request->aadharFrontImage->move(public_path('subscriber/driver/aadhar'), $aadharFrontImage);
            $driver->aadharFrontImage = $aadharFrontImage;
        }
        if ($request->hasFile('aadharBackImage')) {
            $aadharBackImage = time() . '.' . $request->aadharBackImage->extension();
            $request->aadharBackImage->move(public_path('subscriber/driver/aadhar/back'), $aadharBackImage);
            $driver->aadharBackImage = $aadharBackImage;
        }
        if ($request->hasFile('customerdocument')) {
            $customerdocument = time() . '.' . $request->customerdocument->extension();
            $request->customerdocument->move(public_path('subscriber/driver/document'), $customerdocument);
            $driver->customerdocument = $customerdocument;
        }
        if ($request->hasFile('rcbook')) {
            $rcbook = time() . '.' . $request->rcbook->extension();
            $request->rcbook->move(public_path('subscriber/driver/rcbook'), $rcbook);
            $driver->rcbook = $rcbook;
        }
        if ($request->hasFile('drivingLicence')) {
            $drivingLicence = time() . '.' . $request->drivingLicence->extension();
            $request->drivingLicence->move(public_path('subscriber/driver/drivingLicence'), $drivingLicence);
            $driver->drivingLicence = $drivingLicence;
        }
        if ($request->hasFile('insurance')) {
            $insurance = time() . '.' . $request->insurance->extension();
            $request->insurance->move(public_path('subscriber/driver/insurance'), $insurance);
            $driver->insurance = $insurance;
        }
        if ($request->hasFile('bike')) {
            $bike = time() . '.' . $request->bike->extension();
            $request->bike->move(public_path('subscriber/driver/bike'), $bike);
            $driver->bike = $bike;
        }
        $driver->vehicleNo = $request->get('vehicleNo');
        $driver->vehicleModelNo = $request->get('vehicleModelNo');
        $changes = $driver->getDirty();
        $driver->update();
        $data = json_encode($changes, true);
        $notify = new Drivernotify();
        $notify->datas = $data;
        $notify->modifiedBy = implode(",", array('Admin', Auth::id()));
        $notify->modifiedId = $id;
        $notify->message = $request->get('comments');
        $notify->save();
        //dd($changes);
        $driver = Driver::find($id);
        $u = User::where('id', $driver->userid)->update([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'phone' => $request->get('mobile'),
            'dob' => $request->get('dob'),
            'gender' => $request->get('gender'),
        ]);
        if ($request->get('password') != "") {
            $hashedPassword = Hash::make($request->get('password'));
            User::where('id', $driver->userid)->update([
                'password' => $hashedPassword,
            ]);
        }
        if ($request->hasFile('profile')) {
            $imageName = uniqid() . "." . $request->profile->extension();
            $request->profile->move(public_path('subscriber/driver/profile/'), $imageName);
            User::where('id', $driver->userid)->update([
                'image' => $imageName
            ]);
        }
        // $u->dop = $request->get('dob');
        // $u->gender = $request->get('gender');
        return redirect()->route('drivers')->with('success', 'Driver Updated');
    }
    public function drivernotify()
    {
        $driver = Drivernotify::join('driver', 'driver.id', '=', 'driver_notify.modifiedId')->join('subscriber', 'subscriber.id', '=', 'driver_notify.modifiedBy')->orderBy('driver_notify.id', 'DESC')->get(array('driver_notify.*', 'driver.name as drivername', 'subscriber.name as subscribername', 'subscriber.subscriberId as subscriberid'));
        // dd($driver);
        $driver_ids = [];
        foreach ($driver as $single_driver) {
            $driver_ids[] = $single_driver->modifiedId;
        }
        $driver_details = Driver::query()->whereIn('id', $driver_ids)->get();
        $user_ids = [];
        foreach ($driver_details as $drivers) {
            $user_ids[] = $drivers->userid;
        }
        $user_details = User::query()->whereIn('id', $user_ids)->get();
        //dd($user_ids);
        return view('admin.notification.driver', compact('driver', 'user_details'));
    }
    public function dotnotify()
    {
        //dd('hi');
        $result = [];
        $noti = false;
        //$count = Drivernotify::where('readBy', null)->latest()->count();
        $count = Schema::hasColumn('driver_notify', 'readBy')
            ? Drivernotify::whereNull('readBy')
                ->where('modifiedBy', 'REGEXP', '^[0-9]+$')
                ->latest()
                ->count()
            : 0;
        if ($count > 0) {
            $noti = true;
        }
        $sub = false;
        $sub_count = Pricenotify::whereNull('readBy')
            ->where(function ($q) {
                $q->where('pricenotify.datas', 'LIKE', "%name%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%location%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%description%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%password%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%email%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%mobile%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%pincode%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%aadharImage%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%aadharBackImage%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%aadharNo%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%pancardImage%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%bankstatement%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%account_type%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%customerdocument%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%image%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%video%");
            })
            //dd($sub_count);

            ->latest()
            ->count();
        if ($sub_count > 0) {
            $sub = true;
        }
        $result['noti'] = $noti;
        $result['sub'] = $sub;
        return response()->json($result);
    }

    public function readBy(Drivernotify $readBy)
    {
        if (!Schema::hasColumn('driver_notify', 'readBy')) {
            return back()->with('success', "Message read Successfully");
        }
        $readBy->update(['readBy' => Auth::id()]);
        return back()->with('success', "Message read Successfully");
    }
    public function subscriberblockList()
    {
        $blocklist = Blocklist::join('subscriber', 'subscriber.id', '=', 'blocklist.blockedId')->where('table', 'subscriber')->get(array('blocklist.*', 'subscriber.name as subscribername', 'subscriber.status as subscriberstatus', 'subscriber.subscriberId as subscriberId'));
        //   dd($blocklist);
        //   $blocklist = Blocklist::where('table',subscriber);
        return view('admin.block.subscriber', compact('blocklist'));
    }
    public function driverblockList()
    {
        // $blocklist=Blocklist::join('driver','driver.id', '=', 'blocklist.blockedId')->where('table','driver')->paginate(16, array('blocklist.*','driver.name as drivername' ));
        $blocklist = SubBlock::with('driver', 'subscriber')
            ->latest()->get();
        // dd($blocklist);
        return view('admin.block.driver', compact('blocklist'));
    }
    public function adminBlockeddriver()
    {
        $blocklist = Blocklist::join('driver', 'driver.id', '=', 'blocklist.blockedId')->where([['table', 'driver']])->get(array('blocklist.*', 'driver.name as drivername', 'driver.status as driverstatus', 'driver.userid as driverId'));

        return view('admin.block.admindriver', compact('blocklist'));
    }
    public function subscriberunblockList()
    {
        $unblocklist = Unblocklist::join('subscriber', 'subscriber.id', '=', 'unblocklist.unblockedId')->where('table', 'subscriber')->get(array('unblocklist.*', 'subscriber.name as subscribername', 'subscriber.status as subscriberstatus', 'subscriber.subscriberId as subscriberId'));
        // dd($unblocklist);
        return view('admin.unblock.subscriber', compact('unblocklist'));
    }
    public function driverunblockList()
    {
        $unblocklist = SubUnblock::with('driver', 'subscriber')->latest()->get();
        // dd($unblocklist);
        return view('admin.unblock.driver', compact('unblocklist'));
    }
    public function adminUnblockeddriver()
    {
        $unblocklist = Unblocklist::join('driver', 'driver.id', '=', 'unblocklist.unblockedId')->where([['table', 'driver']])->get(array('unblocklist.*', 'driver.name as drivername', 'driver.status as driverstatus', 'driver.userid as driverId'));

        return view('admin.unblock.admindriver', compact('unblocklist'));
    }

    // Coupouns
    public function coupons()

    {
        $coupons = Coupons::get();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function createcoupons()
    {
        return view('admin.coupons.create');
    }
    public function couponsstore(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'code' => 'required|unique:coupons,code',
            'user_limit' => 'required',
            'valid_from' => 'required',
            'valid_to' => 'required',

        ]);


        $coupons = new Coupons();
        $coupons->name = $request->get('name');
        $coupons->code = $request->get('code');
        $coupons->user_limit = $request->get('user_limit');
        $coupons->valid_from = $request->get('valid_from');
        $coupons->valid_to = $request->get('valid_to');
        $coupons->description = $request->get('description');
        $coupons->active = $request->get('active');
        $coupons->save();


        //return back()->with('success', 'You have just created one pincode');
        return redirect('coupons')->with('success', 'Coupons created!');
    }
    public function couponsedit($id)
    {
        $coupons = Coupons::find($id);
        return view('admin.coupons.edit', compact('coupons'));
    }


    public function couponsupdate(Request $request, $id)
    {

        $this->validate($request, [
            'name' => 'required',
            'code' => 'required',
            'user_limit' => 'required',
            'valid_from' => 'required',
            'valid_to' => 'required',

        ]);

        $coupons =  Coupons::findorFail($id);

        $coupons->name = $request->get('name');
        $coupons->code = $request->get('code');
        $coupons->user_limit = $request->get('user_limit');
        $coupons->valid_from = $request->get('valid_from');
        $coupons->valid_to = $request->get('valid_to');
        $coupons->description = $request->get('description');
        $coupons->active = $request->get('active');

        $coupons->update();

        return redirect('coupons')->with('status', 'Coupons Updated ');
    }
    public function couponsdestroy($id)
    {
        Coupons::find($id)->delete();
        return back()->with('success', 'Coupons deleted successfully');
    }


    public function couponsActivate(Request $request)
    {

        $data = Coupons::findOrFail($request->id);
        $data->active = $request->status;
        $data->save();



        return response()->json(['success' => 'Coupon status updated successfully.']);
    }

    //Price Notification
    public function subscriberpriceNotify()
    {

        $notify = Pricenotify::join('subscriber', 'subscriber.id', '=', 'pricenotify.modifiedId')
            ->leftJoin('admin', 'admin.id', '=', 'pricenotify.readBy')
            ->where(function ($q) {
                $q->where('pricenotify.datas', 'LIKE', '%price%')
                    ->orWhere('pricenotify.datas', 'LIKE', "%bankacno%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%ifsccode%");
            })
            //->orWhere('pricenotify.datas', 'LIKE', "%description%")
            //->orWhere('pricenotify.datas', 'LIKE', "%password%")
            //->orWhere('pricenotify.datas', 'LIKE', "%email%")
            //->orWhere('pricenotify.datas', 'LIKE', "%mobile%")
            //->orWhere('pricenotify.datas', 'LIKE', "%pincode%")
            //->orWhere('pricenotify.datas', 'LIKE', "%aadharImage%")
            //->orWhere('pricenotify.datas', 'LIKE', "%aadharBackImage%")
            //->orWhere('pricenotify.datas', 'LIKE', "%aadharNo%")
            //->orWhere('pricenotify.datas', 'LIKE', "%pancardImage%")
            //->orWhere('pricenotify.datas', 'LIKE', "%bankstatement%")
            //->orWhere('pricenotify.datas', 'LIKE', "%account_type%")
            //->orWhere('pricenotify.datas', 'LIKE', "%customerdocument%")
            //->orWhere('pricenotify.datas', 'LIKE', "%image%")
            //->orWhere('pricenotify.datas', 'LIKE', "%video%")
            ->orderBy('pricenotify.created_at', 'DESC') // Order the records by 'created_at' in descending order
            ->get(['pricenotify.*', 'pricenotify.read as read', 'subscriber.name as subscribername', 'subscriber.subscriberId as subscriberId', 'admin.emp_id as adminname']);
        // dd($notify);
        return view('admin.subscriber.pricenotify', compact('notify'));
    }

    public function subscriberNotification()
    {

        // Create a subquery to filter the records based on 'pricenotify.datas'
        $notify = Pricenotify::join('subscriber', 'subscriber.id', '=', 'pricenotify.modifiedId')
            ->leftJoin('admin', 'admin.id', '=', 'pricenotify.readBy')
            ->where(function ($q) {
                $q->where('pricenotify.datas', 'LIKE', '%name%')
                    ->orWhere('pricenotify.datas', 'LIKE', "%location%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%location%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%description%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%password%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%email%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%mobile%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%pincode%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%aadharImage%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%aadharBackImage%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%aadharNo%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%pancardImage%")
                    // ->orWhere('pricenotify.datas', 'LIKE', "%bankstatement%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%account_type%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%customerdocument%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%image%")
                    ->orWhere('pricenotify.datas', 'LIKE', "%video%");
            })
            ->orderBy('pricenotify.created_at', 'DESC') // Order the records by 'created_at' in descending order
            ->get(['pricenotify.*', 'pricenotify.read as read', 'subscriber.name as subscribername', 'subscriber.subscriberId as subscriberId', 'admin.emp_id as adminname']);
        // dd($notify);
        return view('admin.subscriber.notify', compact('notify'));
    }

    public function markread(Request $request)
    {

        $user = Pricenotify::findOrFail($request->id);
        $user->read = 1;
        $user->save();



        return response()->json(['success' => 'Marked as seen']);
    }

    public function markasread(Request $request)
    {

        $user = Pricenotify::findOrFail($request->input('id'));
        $user->read = 1;
        $user->readBy =  Auth::id();
        $user->save();


        // return Session::get('subscribers');
        // return  Auth::id();
        return redirect()->back();
    }
}
