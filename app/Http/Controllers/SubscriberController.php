<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Pincode;
use App\Models\Category;
use App\Models\Pincodebasedcategory;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Models\Blocklist;
use App\Models\Unblocklist;
use App\Models\Driver;
use App\Models\Employee;
use App\Models\Price;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\statusnotify;
use App\Models\Pricenotify;
use App\Models\Booking;
use App\Services\WhatsAppOtpService;
use Validator;
use App\Models\site;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class SubscriberController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except([
            'create', 'subscriberstore', 'showOtpVerification', 'verifyOtp', 'resendOtp'
        ]);
        $this->middleware('permission:subscriber-list|subscriber-create|subscriber-edit|subscriber-delete', ['only' => ['subscriber', 'createsubscriber']]);
        $this->middleware('permission:subscriber-create', ['only' => ['subscriber']]);
        $this->middleware('permission:subscriber-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:subscriber-delete', ['only' => ['destroy']]);
        // $this->middleware('permission:driver-driverblock', ['only' => ['driverblock']]);
    }

    public function zipcodeE()
    {
        $zipcode = Pincode::where('usedBy', '!=', 0)
            ->select('id', 'usedBy') // Specify columns to select
            ->get();
        $categories = Category::pluck('id');
        foreach ($zipcode as $code) {
            foreach ($categories as $category) {
                Pincodebasedcategory::create([
                    'subscriber_id' => $code->usedBy,
                    'pincode_id' => $code->id,
                    'category_id' => $category
                ]);
            }
        }
        dd("Done!!!");
    }

    public function subscriber()

    {
        $adminIds = Admin::pluck('id')->map(function ($id) {
            return (string) $id;
        })->all();

        $subscriber = Subscriber::whereIn('created_by', $adminIds)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();
        //dd($subscriber);
        $pincode = Pincode::all();
        $id = $subscriber->pluck('created_by');
        $role = Role::whereIn('id', $id)->get();

        $empolyee_id = Admin::whereIn('id', $id)->get();
        $emp_id = $empolyee_id->pluck('emp_id');
        // dd($emp_id);
        $roleName = $emp_id;
        // dd($roleName);
        return view('admin.subscriber.index', compact('subscriber', 'pincode', 'roleName'));
    }

    public function subscribersWithoutEmployeeId()
    {
        $adminIds = Admin::pluck('id')->map(function ($id) {
            return (string) $id;
        })->all();

        $subscriber = Subscriber::where(function ($query) use ($adminIds) {
            $query->whereNull('created_by')
                ->orWhere('created_by', '')
                ->orWhere('created_by', '0')
                ->orWhere('created_by', 'public');

            if (!empty($adminIds)) {
                $query->orWhereNotIn('created_by', $adminIds);
            }
        })
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        $pincode = Pincode::all();
        $roleName = collect();
        $pageTitle = 'Self Registered Subscribers';
        $showMissingEmployeeList = false;

        return view('admin.subscriber.index', compact(
            'subscriber',
            'pincode',
            'roleName',
            'pageTitle',
            'showMissingEmployeeList'
        ));
    }

    public function create()
    {
        $pincode = Pincode::availableForNewSubscriber()->get();
        return view('admin.subscriber.create', compact('pincode'));
    }
    public function subscriberstore(Request $request, WhatsAppOtpService $whatsAppOtp)
    {
        $isPublicRegistration = !Auth::check();
        // dd($request);
        $this->validate($request, [
            'name' => 'required',
            'location' => 'required',
            'subscriptionDate' => 'required',
            'email' => 'required',
            'mobile' => ['required', 'max:12'],
            'pincode' => 'required|array|min:1|max:5',
            'pincode.*' => [
                'required',
                'integer',
                'exists:pincode,id',
                Rule::notIn(Pincode::unavailableForNewSubscriberIds()),
            ],
            'password' => 'required',

            'aadharNo' => 'required|numeric|unique:subscriber,aadharNo',
            'aadharImage' => 'required|mimes:pdf',
            'aadharBackImage' => 'required|mimes:pdf',
            'pancardImage' => 'required|mimes:pdf',
            'bankstatement' => 'required|mimes:pdf',
            'account_type' => 'required',
            'customerdocument' => 'mimes:pdf',
            'image' => 'required',
            'biketaxi_price' => 'required',
            'pickup_price' => 'required',
            'buy_price' => 'required',
            'auto_price' => 'required',
            'cab_price' => 'required',
            'bt_price1' => 'required|numeric',
            'bt_price2' => 'required|numeric',
            'bt_price3' => 'required|numeric',
            'bt_price4' => 'required|numeric',
            'pk_price1' => 'required|numeric',
            'pk_price2' => 'required|numeric',
            'pk_price3' => 'required|numeric',
            'pk_price4' => 'required|numeric',
            'bd_price1' => 'required|numeric',
            'bd_price2' => 'required|numeric',
            'bd_price3' => 'required|numeric',
            'bd_price4' => 'required|numeric',
            'at_price1' => 'required|numeric',
            'at_price2' => 'required|numeric',
            'at_price3' => 'required|numeric',
            'at_price4' => 'required|numeric',
            'cab_price1' => 'required|numeric',
            'cab_price2' => 'required|numeric',
            'cab_price3' => 'required|numeric',
            'cab_price4' => 'required|numeric',
            'bankacno' => 'required',
            'ifsccode' => 'required',
            'video' => 'nullable|mimes:mp4',
            'qr' => 'nullable'
        ]);
        $pincode = array();
        $zipcode = $request->pincode;
        //dd($zipcode);
        $pincode = json_encode($request->pincode);
        $date = now()->format('Y-m-d');
        if (Auth::check()) {
            try {
                $date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->get('subscriptionDate'))->format('Y-m-d');
            } catch (\Exception $e) {
                $date = \Carbon\Carbon::parse($request->get('subscriptionDate'))->format('Y-m-d');
            }
        }
        $date1 = $request->get('expiryDate');
        $subscriptionDate = $date;

        $expiryDate = null;
        if (!empty($date1)) {
            try {
                $expiryDate = \Carbon\Carbon::createFromFormat('d-m-Y', $date1)->startOfDay()->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                try {
                    $expiryDate = \Carbon\Carbon::createFromFormat('Y-m-d', $date1)->startOfDay()->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    $expiryDate = null;
                }
            }
        }

        if ($expiryDate === null) {
            $expiryDate = \Carbon\Carbon::parse($subscriptionDate)->addDays(28)->format('Y-m-d H:i:s');
        }

        $subscriber = new Subscriber();
        $subscriber->account_type = $request->get('account_type');
        $subscriber->name = $request->get('name');
        $subscriber->location = $request->get('location');
        $subscriber->subscriptionDate = $subscriptionDate;
        $subscriber->subscriberId = 'DKS-' . uniqid();
        $subscriber->expiryDate = $expiryDate;
        $subscriber->subscription_price = $request->get('subscriptionPrice');
        $subscriber->description = $request->get('description');
        $subscriber->email = $request->get('email');
        $subscriber->mobile = $request->get('mobile');
        $subscriber->pincode = $pincode;
        $subscriber->password = $request->get('password');
        $subscriber->aadharNo = $request->get('aadharNo');
        $subscriber->bankacno = $request->get('bankacno');
        $subscriber->ifsccode = $request->get('ifsccode');
        $subscriber->created_by = Auth::id() ?? 'public';
        $aadharImage = time() . '.' . $request->aadharImage->extension();
        $request->aadharImage->move(public_path('admin/subscriber/aadhar'), $aadharImage);
        $subscriber->aadharImage = $aadharImage;


        if ($request->hasFile('video')) {
            $video = time() . '.' . $request->video->extension();
            $request->video->move(public_path('admin/subscriber/video'), $video);
            $subscriber->video = $video;
        }


        $aadharBackImage = time() . '.' . $request->aadharBackImage->extension();
        $request->aadharBackImage->move(public_path('admin/subscriber/aadhar/back'), $aadharBackImage);
        $subscriber->aadharBackImage = $aadharBackImage;

        $pancardImage = time() . '.' . $request->pancardImage->extension();
        $request->pancardImage->move(public_path('admin/subscriber/pan'), $pancardImage);
        $subscriber->pancardImage = $pancardImage;

        $bankstatement = time() . '.' . $request->bankstatement->extension();
        $request->bankstatement->move(public_path('admin/subscriber/bankstatement'), $bankstatement);
        $subscriber->bankstatement = $bankstatement;

        $image = time() . '.' . $request->image->extension();
        if ($request->hasFile('customerdocument')) {
            $customerdocument = time() . '.' . $request->customerdocument->extension();
            $request->customerdocument->move(public_path('admin/subscriber/document'), $customerdocument);
            $subscriber->customerdocument = $customerdocument;
        }
        $request->image->move(public_path('admin/subscriber/profile'), $image);
        $subscriber->image = $image;
        if ($request->hasFile('qr')) {
            $qr = uniqid() . "." . $request->qr->extension();
            $request->qr->move(public_path('qr'), $qr);
            $subscriber->qr = $qr;
        }
        $subscriber->biketaxi_price = $request->get('biketaxi_price');
        $subscriber->pickup_price = $request->get('pickup_price');
        $subscriber->buy_price = $request->get('buy_price');
        $subscriber->auto_price = $request->get('auto_price');
        $subscriber->cab_price = $request->get('cab_price');
        $subscriber->bt_price1 = $request->get('bt_price1');
        $subscriber->bt_price2 = $request->get('bt_price2');
        $subscriber->bt_price3 = $request->get('bt_price3');
        $subscriber->bt_price4 = $request->get('bt_price4');
        $subscriber->pk_price1 = $request->get('pk_price1');
        $subscriber->pk_price2 = $request->get('pk_price2');
        $subscriber->pk_price3 = $request->get('pk_price3');
        $subscriber->pk_price4 = $request->get('pk_price4');
        $subscriber->bd_price1 = $request->get('bd_price1');
        $subscriber->bd_price2 = $request->get('bd_price2');
        $subscriber->bd_price3 = $request->get('bd_price3');
        $subscriber->bd_price4 = $request->get('bd_price4');
        $subscriber->at_price1 = $request->get('at_price1');
        $subscriber->at_price2 = $request->get('at_price2');
        $subscriber->at_price3 = $request->get('at_price3');
        $subscriber->at_price4 = $request->get('at_price4');
        $subscriber->cab_price1 = $request->get('cab_price1');
        $subscriber->cab_price2 = $request->get('cab_price2');
        $subscriber->cab_price3 = $request->get('cab_price3');
        $subscriber->cab_price4 = $request->get('cab_price4');
        $subscriber->blockedstatus = 1;
        if ($isPublicRegistration) {
            $subscriber->status = 0;
            $subscriber->activestatus = 0;
        }
        $subscriber->save();
        $subid = $subscriber->id;
        $insertPric = $this->storePrice($subid, $zipcode, $request);
        // dd($subscriber);

        if (!$isPublicRegistration) {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                // 'gender' => $request->gender,
                'password' => $request->password,
                'address' => $request->description,
                'aadhar' => $request->aadharNo,
                'subscriber_id' => $subscriber->id,
                'emp_id' => "PBP Employee ID - " . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT),
                'role' => 'Subscriber Admin'
            ];
            $role = Role::where('guard_name', 'subscriber')->where('name', $data['role'])->first();
            Employee::create($data);
            if ($role) {
                $subscriber->assignRole($role->name);
            }
        }

        $categories = Category::pluck('id');
        foreach ($zipcode as $code) {
            foreach ($categories as $category) {
                Pincodebasedcategory::create([
                    'subscriber_id' => $subscriber->id,
                    'pincode_id' => $code,
                    'category_id' => $category
                ]);
            }
        }

        if ($isPublicRegistration && config('services.whatsapp.onboarding_otp_enabled')) {
            $otp = (string) random_int(100000, 999999);
            $this->storeOnboardingOtp($subscriber, $otp);
            $sent = $whatsAppOtp->send($subscriber->mobile, $otp);

            return redirect()->route('subscriber.otp.form')->with(
                $sent ? 'success' : 'error',
                $sent
                    ? 'Verification code sent to your WhatsApp number.'
                    : 'WhatsApp OTP is not configured yet. Please use Resend OTP after the token is configured.'
            );
        }

        $message = 'Application Submitted Successfully. Your application has been received and is under review. The review process typically takes 5-7 business days. You will receive a WhatsApp notification if your application is approved or rejected. If additional information is required, our team may contact you through our official WhatsApp number: 9069067008. If your application remains under review beyond 7 business days, you may contact us through our official WhatsApp number using your registered mobile number and Service Provider ID. To ensure timely processing for all applicants, please avoid sending repeated follow-up messages during the review period.';
        return redirect()->route('createSubscriber')->with([
            'success' => 'Subscriber added!',
            'success_message' => $message,
            'show_success_modal' => true
        ]);
    }

    public function showOtpVerification()
    {
        $subscriber = $this->pendingOtpSubscriber();
        if (!$subscriber) {
            return redirect()->route('createSubscriber')
                ->with('error', 'Your verification session expired. Please submit the form again.');
        }

        $digits = preg_replace('/\D+/', '', (string) $subscriber->mobile);
        $maskedPhone = strlen($digits) > 4
            ? str_repeat('*', strlen($digits) - 4) . substr($digits, -4)
            : $digits;

        return view('admin.subscriber.verify-otp', compact('maskedPhone'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => ['required', 'digits:6']]);

        $subscriber = $this->pendingOtpSubscriber();
        $hash = session('subscriber_onboarding_otp.hash');
        $expiresAt = session('subscriber_onboarding_otp.expires_at');
        $attempts = (int) session('subscriber_onboarding_otp.attempts', 0);

        if (!$subscriber || !$hash || !$expiresAt || now()->timestamp > (int) $expiresAt) {
            return back()->with('error', 'OTP expired. Please request a new code.');
        }

        if ($attempts >= 5) {
            return back()->with('error', 'Too many incorrect attempts. Please request a new code.');
        }

        if (!Hash::check($request->otp, $hash)) {
            session(['subscriber_onboarding_otp.attempts' => $attempts + 1]);
            return back()->withErrors(['otp' => 'The verification code is incorrect.']);
        }

        $subscriber->phone_verified_at = now();
        $subscriber->save();
        session()->forget('subscriber_onboarding_otp');

        return redirect()->route('createSubscriber')->with([
            'success' => 'Phone number verified!',
            'success_message' => 'Application Submitted Successfully. Your phone number has been verified and your application is under review.',
            'show_success_modal' => true,
        ]);
    }

    public function resendOtp(WhatsAppOtpService $whatsAppOtp)
    {
        $subscriber = $this->pendingOtpSubscriber();
        if (!$subscriber) {
            return redirect()->route('createSubscriber')
                ->with('error', 'Your verification session expired. Please submit the form again.');
        }

        $otp = (string) random_int(100000, 999999);
        $this->storeOnboardingOtp($subscriber, $otp);

        if (!$whatsAppOtp->send($subscriber->mobile, $otp)) {
            return back()->with('error', 'We could not send the WhatsApp code. Please configure the token or try again shortly.');
        }

        return back()->with('success', 'A new verification code was sent to your WhatsApp number.');
    }

    private function storeOnboardingOtp(Subscriber $subscriber, string $otp): void
    {
        session([
            'subscriber_onboarding_otp' => [
                'subscriber_id' => $subscriber->id,
                'hash' => Hash::make($otp),
                'expires_at' => now()->addMinutes(10)->timestamp,
                'attempts' => 0,
            ],
        ]);
    }

    private function pendingOtpSubscriber(): ?Subscriber
    {
        $subscriberId = session('subscriber_onboarding_otp.subscriber_id');
        if (!$subscriberId) {
            return null;
        }

        return Subscriber::where('id', $subscriberId)
            ->where('created_by', 'public')
            ->whereNull('phone_verified_at')
            ->first();
    }

    public function edit($id)
    {
        $subscriber = Subscriber::findOrFail($id);

        $pincode = Pincode::availableForSubscriber($subscriber)->get();
        //dd($pincode);
        return view('admin.subscriber.edit', compact('subscriber', 'pincode'));
    }
    public function update(Request $request, $id)
    {
        $user = Subscriber::where('id', $id)->first();
        // dd($user);
        $employee = Employee::where('email', $user->email)->first();
        // dd($employee);

        $currentPincodeIds = collect(json_decode((string) $user->pincode, true))
            ->map(fn ($pincodeId) => (int) $pincodeId)
            ->filter()
            ->unique()
            ->all();
        $unavailablePincodeIds = array_diff(
            Pincode::unavailableForNewSubscriberIds($user->id),
            $currentPincodeIds
        );

        $this->validate($request, [
            'name' => 'required',
            'location' => 'required',
            'subscriptionDate' => 'required',
            'expiryDate' => 'required',
            'email' => 'required',
            'mobile' => ['required', 'max:12'],
            'pincode' => 'required|array|min:1|max:5',
            'pincode.*' => [
                'required',
                'integer',
                'exists:pincode,id',
                Rule::notIn($unavailablePincodeIds),
            ],
            'password' => 'required',

            'aadharNo' => 'required|numeric',
            'bankacno' => 'required',
            'ifsccode' => 'required',
            'account_type' => 'required',
            'biketaxi_price' => 'required',
            'pickup_price' => 'required',
            'buy_price' => 'required',
            'auto_price' => 'required',
            'cab_price' => 'required',
            'bt_price1' => 'required|numeric',
            'bt_price2' => 'required|numeric',
            'bt_price3' => 'required|numeric',
            'bt_price4' => 'required|numeric',
            'pk_price1' => 'required|numeric',
            'pk_price2' => 'required|numeric',
            'pk_price3' => 'required|numeric',
            'pk_price4' => 'required|numeric',
            'bd_price1' => 'required|numeric',
            'bd_price2' => 'required|numeric',
            'bd_price3' => 'required|numeric',
            'bd_price4' => 'required|numeric',
            'at_price1' => 'required|numeric',
            'at_price2' => 'required|numeric',
            'at_price3' => 'required|numeric',
            'at_price4' => 'required|numeric',
            'cab_price1' => 'required|numeric',
            'cab_price2' => 'required|numeric',
            'cab_price3' => 'required|numeric',
            'cab_price4' => 'required|numeric',
            'video' => 'nullable|mimes:mp4',
            'qr' => 'nullable'
        ]);

        $data = [
            'name' => $request?->name,
            'email' => $request?->email,
            'mobile' => $request?->mobile,
            'address' => $request?->description,
            'aadhar' => $request?->aadharNo,
        ];
        $employee?->update($data);
        // dd("done");
        $pincode = array();
        $zipcode = $request->pincode;

        //dd($zipcode);
        $subid = $id;
        $pincode = json_encode($request->pincode);
        $date = $request->get('subscriptionDate');
        // dd($date);
        $date1 = $request->get('expiryDate');
        //$subscriptionDate = \Carbon\Carbon::createFromFormat('Y-m-d h:i:s', $date)->format('d-m-Y');
        $subscriptionDate  = $date;



        $expiryDate = \Carbon\Carbon::createFromFormat('Y-m-d h:i:s', $date1)
            ->format('d-m-Y');
        $subscriber = Subscriber::findorFail($id);
        $subscriber->account_type = $request->get('account_type');
        $subscriber->name = $request->get('name');
        $subscriber->location = $request->get('location');
        $subscriber->subscriptionDate = $subscriptionDate;
        $subscriber->expiryDate = $expiryDate;
        $subscriber->subscription_price = $request->get('subscriptionPrice');
        $subscriber->description = $request->get('description');
        $subscriber->email = $request->get('email');
        $subscriber->mobile = $request->get('mobile');
        $subscriber->pincode = $pincode;
        $subscriber->password = $request->get('password');
        $subscriber->bankacno = $request->get('bankacno');
        $subscriber->ifsccode = $request->get('ifsccode');
        $subscriber->aadharNo = $request->get('aadharNo');
        if ($request->hasFile('aadharImage')) {
            $aadharImage = time() . '.' . $request->aadharImage->extension();
            $request->aadharImage->move(public_path('admin/subscriber/aadhar'), $aadharImage);
            $subscriber->aadharImage = $aadharImage;
        }
        if ($request->hasFile('customerdocument')) {
            $customerdocument = time() . '.' . $request->customerdocument->extension();
            $request->customerdocument->move(public_path('admin/subscriber/document'), $customerdocument);
            $subscriber->customerdocument = $customerdocument;
        }
        if ($request->hasFile('aadharBackImage')) {
            $aadharBackImage = time() . '.' . $request->aadharBackImage->extension();
            $request->aadharBackImage->move(public_path('admin/subscriber/aadhar/back'), $aadharBackImage);
            $subscriber->aadharBackImage = $aadharBackImage;
        }
        if ($request->hasFile('pancardImage')) {
            $pancardImage = time() . '.' . $request->pancardImage->extension();
            $request->pancardImage->move(public_path('admin/subscriber/pan'), $pancardImage);
            $subscriber->pancardImage = $pancardImage;
        }
        if ($request->hasFile('bankstatement')) {
            $bankstatement = time() . '.' . $request->bankstatement->extension();
            $request->bankstatement->move(public_path('admin/subscriber/bankstatement'), $bankstatement);
            $subscriber->bankstatement = $bankstatement;
        }

        if ($request->hasFile('video')) {

            $video = time() . '.' . $request->video->extension();
            $request->video->move(public_path('admin/subscriber/video'), $video);
            $subscriber->video = $video;
        }

        if ($request->hasFile('image')) {
            $image = time() . '.' . $request->image->extension();
            $request->image->move(public_path('admin/subscriber/profile'), $image);
            $subscriber->image = $image;
        }

        if ($request->hasFile('qr')) {
            $qr = uniqid() . '.' . $request->qr->extension();
            $request->qr->move(public_path('qr'), $qr);
            $subscriber->qr = $qr;
        }
        $subscriber->biketaxi_price = $request->get('biketaxi_price');
        $subscriber->pickup_price = $request->get('pickup_price');
        $subscriber->buy_price = $request->get('buy_price');
        $subscriber->auto_price = $request->get('auto_price');
        $subscriber->cab_price = $request->get('cab_price');
        $subscriber->bt_price1 = $request->get('bt_price1');
        $subscriber->bt_price2 = $request->get('bt_price2');
        $subscriber->bt_price3 = $request->get('bt_price3');
        $subscriber->bt_price4 = $request->get('bt_price4');
        $subscriber->pk_price1 = $request->get('pk_price1');
        $subscriber->pk_price2 = $request->get('pk_price2');
        $subscriber->pk_price3 = $request->get('pk_price3');
        $subscriber->pk_price4 = $request->get('pk_price4');
        $subscriber->bd_price1 = $request->get('bd_price1');
        $subscriber->bd_price2 = $request->get('bd_price2');
        $subscriber->bd_price3 = $request->get('bd_price3');
        $subscriber->bd_price4 = $request->get('bd_price4');
        $subscriber->at_price1 = $request->get('at_price1');
        $subscriber->at_price2 = $request->get('at_price2');
        $subscriber->at_price3 = $request->get('at_price3');
        $subscriber->at_price4 = $request->get('at_price4');
        $subscriber->cab_price1 = $request->get('cab_price1');
        $subscriber->cab_price2 = $request->get('cab_price2');
        $subscriber->cab_price3 = $request->get('cab_price3');
        $subscriber->cab_price4 = $request->get('cab_price4');
        $changes = $subscriber->getDirty();
        $subscriber->update();
        $data = json_encode($changes, true);
        $notify = new Pricenotify();
        $notify->datas = $data;
        $notify->message = $request->get('comments');
        $notify->modifiedId = $subid;
        $notify->modifiedBy = Auth::id();
        $notify->save();
        $insertPric = $this->storePrice($subid, $zipcode, $request);


        $existingpincodebasedcategory = Pincodebasedcategory::where('subscriber_id', $subscriber->id)
            ->select('pincode_id')
            ->distinct('pincode_id')
            ->get();
        //dd($existingpincodebasedcategory);
        foreach ($existingpincodebasedcategory as $category) {
            if (!in_array($category->pincode_id, $zipcode)) {
                $categories = Pincodebasedcategory::where('pincode_id', $category->pincode_id)
                    ->get();
                foreach ($categories as $categ) {
                    $pincode = Pincode::where('id', $categ->pincode_id)
                        ->first();
                    $pincode->update([
                        'usedBy' => 0,
                    ]);
                    $prices = Price::where('pincode', $pincode->pincode)
                        ->get();
                    if (count($prices) > 0) {
                        foreach ($prices as $price) {
                            $price->delete();
                        }
                    }
                    $categ->delete();
                }
            }
        }
        $categories = Category::pluck('id');
        foreach ($zipcode as $code) {
            foreach ($categories as $category) {
                $pincodebasedcategory = Pincodebasedcategory::where('pincode_id', $code)
                    ->where('category_id', $category)
                    ->first();
                if (!isset($pincodebasedcategory)) {
                    Pincodebasedcategory::create([
                        'subscriber_id' => $subscriber->id,
                        'pincode_id' => $code,
                        'category_id' => $category
                    ]);
                }
            }
        }


        return redirect('subscriberList')->with('success', 'Subscriber Updated');
    }
    public function changeStatus(Request $request)
    {

        $user = Subscriber::findOrFail($request->user_id);
        $user->status = $request->status;
        // dd($user);
        $user->save();

        return response()->json(['success' => 'Status updated successfully.']);
    }

    public function show($id)
    {
        $subscriber = Subscriber::find($id);
        $pin = json_decode($subscriber->pincode);
        $pincode = Pincode::whereIn('id', $pin)->get();
        $pricenotify = Pricenotify::where('modifiedId', $id)->latest()->get();
        $statusnotify = statusnotify::where('modifiedId', $id)->latest()->get();
        $empolyee_id = Employee::where('email', $subscriber->email)->first()->emp_id ?? null;
        $admin = Admin::all();
        return view('admin.subscriber.show', compact('statusnotify', 'subscriber', 'pincode', "pricenotify", 'admin', 'empolyee_id'));
    }
    public function destroy($id)
    {
        Subscriber::find($id)->delete();
        return back()->with('success', 'Subscriber deleted successfully');
    }

    public function expiry()

    {
        $today = now()->format('Y-m-d');
        $subscriber = Subscriber::whereDate('expiryDate', '>=', $today)->get();
        return view('admin.subscriber.expiry', compact('subscriber', 'today'));
    }
    // public function block(Request $request, $id)
    // {
    //   $subscriber = Subscriber::findorFail($id);

    //   $subscriber->status = '2';
    //   $subscriber->update();

    //   $block = new Blocklist();
    //   $block->table = 'subscriber';
    //   $block->blockedId = $id;
    //   $block->blockedBy = 'Admin';
    //   $block->comments = $request->get('reason');
    //   $block->save();

    //   return redirect('subscriberList')->with('success', 'Subscriber blocked ');
    // }
    // public function subscriberunblock(Request $request)
    // {
    //   $subscriberid = $request->get('sub_id');
    //   $Reason = $request->get('comments');
    //   $subscriber = Subscriber::findorFail($subscriberid);
    //   $subscriber->status = 1;
    //   $subscriber->update();

    //   $unblock = new Unblocklist();
    //   $unblock->table = 'subscriber';
    //   $unblock->unblockedId = $subscriberid;
    //   $unblock->unblockedBy = 'Admin';
    //   $unblock->comments = $request->get('comments');
    //   $unblock->save();
    //   return redirect('subscriberList')->with('success', 'Subscriber unblocked ');
    // }
    public function driverblock(Request $request, $id)
    {
        $driver = Driver::findorFail($id);
        $userid = $driver->userid;
        $driver->status = '2';
        $driver->update();

        $block = new Blocklist();
        $block->table = 'driver';
        $block->blockedId = $id;
        $block->blockedBy = Auth::id();
        $block->comments = $request->get('reason');
        $block->save();
        $bookings = Booking::where('accepted', $driver->userid)
            ->where('status', 0)
            ->orWhere('status', 1)
            ->orWhere('status', 4)
            ->latest()
            ->get();
        //dd($booking);
        //$booking->status = 3;
        // if(isset($booking))
        // {
        //   $booking->update([
        //     'status' => 2,
        //   ]);
        // }
        foreach ($bookings as $booking) {
            $booking->update([
                'status' => 2,
            ]);
        }

        if ($userid != null) {
            $user = User::findorFail($userid);
            $user->is_live = '2';
            $user->update();
            $user = User::findorFail($userid);
            if (isset($user)) {
                $title = "Account Banned";
                $content = "Your account has been banned due to a violation of our terms of service. If you believe this was a mistake, please contact support for assistance.";
                $notification = $this->sendNotification($user, $title, $content);
            }
        }
        return back()->with('success', 'Driver blocked ');
    }
    public function driverunblock(Request $request)
    {
        $driverid = $request->get('sub_id');
        $Reason = $request->get('comments');
        $driver = Driver::findorFail($driverid);
        $userid = $driver->userid;
        $driver->status = 1;
        $driver->update();
        if ($userid != null) {
            $user = User::findorFail($userid);
            $user->is_live = '1';
            $user->update();
        }
        $unblock = new Unblocklist();
        $unblock->table = 'driver';
        $unblock->unblockedId = $driverid;
        $unblock->unblockedBy =  Auth::id();
        $unblock->comments = $request->get('comments');
        $unblock->save();

        return redirect('driver')->with('success', 'Driver unblocked ');
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
                $url = "https://fcm.googleapis.com/v1/projects/donkey-user/messages:send";
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
    public function storePrice($subid, $zipcode, $request)
    {
        foreach ($zipcode as $zip) {
            $getZip = Pincode::find($zip);
            $getZip->usedBy = $subid;
            $getZip->save();
            $zipcode = $getZip->pincode;

            $check = Price::where([['pincode', $zipcode], ['subscriber_id', $subid]])->count();
            if ($check == 0) {
                //Bike taxi
                DB::insert('insert into `price` (subscriber_id,pincode,category,range_from,range_to,amount) values (?, ?, ?, ?, ?, ?)', [$subid, $zipcode, 1, 0, 5, $request->get('bt_price1')]);
                DB::insert('insert into `price` (subscriber_id,pincode,category,range_from,range_to,amount) values (?, ?, ?, ?, ?, ?)', [$subid, $zipcode, 1, 5, 8, $request->get('bt_price2')]);
                DB::insert('insert into `price` (subscriber_id,pincode,category,range_from,range_to,amount) values (?, ?, ?, ?, ?, ?)', [$subid, $zipcode, 1, 8, 10, $request->get('bt_price3')]);
                DB::insert('insert into `price` (subscriber_id,pincode,category,range_from,range_to,amount) values (?, ?, ?, ?, ?, ?)', [$subid, $zipcode, 1, 10, 50, $request->get('bt_price4')]);
                //PickUp
                DB::insert('insert into `price` (subscriber_id,pincode,category,range_from,range_to,amount) values (?, ?, ?, ?, ?, ?)', [$subid, $zipcode, 2, 0, 5, $request->get('pk_price1')]);
                DB::insert('insert into `price` (subscriber_id,pincode,category,range_from,range_to,amount) values (?, ?, ?, ?, ?, ?)', [$subid, $zipcode, 2, 5, 8, $request->get('pk_price2')]);
                DB::insert('insert into `price` (subscriber_id,pincode,category,range_from,range_to,amount) values (?, ?, ?, ?, ?, ?)', [$subid, $zipcode, 2, 8, 10, $request->get('pk_price3')]);
                DB::insert('insert into `price` (subscriber_id,pincode,category,range_from,range_to,amount) values (?, ?, ?, ?, ?, ?)', [$subid, $zipcode, 2, 10, 50, $request->get('pk_price4')]);
                //Drop and delivery
                DB::insert('insert into `price` (subscriber_id,pincode,category,range_from,range_to,amount) values (?, ?, ?, ?, ?, ?)', [$subid, $zipcode, 3, 0, 5, $request->get('bd_price1')]);
                DB::insert('insert into `price` (subscriber_id,pincode,category,range_from,range_to,amount) values (?, ?, ?, ?, ?, ?)', [$subid, $zipcode, 3, 5, 8, $request->get('bd_price2')]);
                DB::insert('insert into `price` (subscriber_id,pincode,category,range_from,range_to,amount) values (?, ?, ?, ?, ?, ?)', [$subid, $zipcode, 3, 8, 10, $request->get('bd_price3')]);
                DB::insert('insert into `price` (subscriber_id,pincode,category,range_from,range_to,amount) values (?, ?, ?, ?, ?, ?)', [$subid, $zipcode, 3, 10, 50, $request->get('bd_price4')]);
                //auto
                DB::insert('insert into `price` (subscriber_id,pincode,category,range_from,range_to,amount) values (?, ?, ?, ?, ?, ?)', [$subid, $zipcode, 4, 0, 5, $request->get('at_price1')]);
                DB::insert('insert into `price` (subscriber_id,pincode,category,range_from,range_to,amount) values (?, ?, ?, ?, ?, ?)', [$subid, $zipcode, 4, 5, 8, $request->get('at_price2')]);
                DB::insert('insert into `price` (subscriber_id,pincode,category,range_from,range_to,amount) values (?, ?, ?, ?, ?, ?)', [$subid, $zipcode, 4, 8, 10, $request->get('at_price3')]);
                DB::insert('insert into `price` (subscriber_id,pincode,category,range_from,range_to,amount) values (?, ?, ?, ?, ?, ?)', [$subid, $zipcode, 4, 10, 50, $request->get('at_price4')]);
                //cab
                DB::insert('insert into `price` (subscriber_id,pincode,category,range_from,range_to,amount) values (?, ?, ?, ?, ?, ?)', [$subid, $zipcode, 5, 0, 5, $request->get('cab_price1')]);
                DB::insert('insert into `price` (subscriber_id,pincode,category,range_from,range_to,amount) values (?, ?, ?, ?, ?, ?)', [$subid, $zipcode, 5, 5, 8, $request->get('cab_price2')]);
                DB::insert('insert into `price` (subscriber_id,pincode,category,range_from,range_to,amount) values (?, ?, ?, ?, ?, ?)', [$subid, $zipcode, 5, 8, 10, $request->get('cab_price3')]);
                DB::insert('insert into `price` (subscriber_id,pincode,category,range_from,range_to,amount) values (?, ?, ?, ?, ?, ?)', [$subid, $zipcode, 5, 10, 50, $request->get('cab_price4')]);
            } else {
                //Bike taxi

                $bt1 = DB::statement(DB::raw('update  `price` SET amount=' . $request->get('bt_price1') . ' where subscriber_id=' . $subid . ' and pincode = ' . $zipcode . ' and category =1 and range_from=0 and range_to=5 '));

                $bt2 = DB::statement(DB::raw('update  `price` SET amount=' . $request->get('bt_price2') . ' where subscriber_id=' . $subid . ' and pincode = ' . $zipcode . ' and category =1 and range_from=5 and range_to=8 '));
                $bt3 = DB::statement(DB::raw('update  `price` SET amount=' . $request->get('bt_price3') . ' where subscriber_id=' . $subid . ' and pincode = ' . $zipcode . ' and category =1 and range_from=8 and range_to=10 '));
                $bt4 = DB::statement(DB::raw('update  `price` SET amount=' . $request->get('bt_price4') . ' where subscriber_id=' . $subid . ' and pincode = ' . $zipcode . ' and category =1 and range_from=10 and range_to=50 '));

                //PickUp
                $pk1 = DB::statement(DB::raw('update  `price` SET amount=' . $request->get('pk_price1') . ' where subscriber_id=' . $subid . ' and pincode = ' . $zipcode . ' and category =2 and range_from=0 and range_to=5 '));
                $pk2 = DB::statement(DB::raw('update  `price` SET amount=' . $request->get('pk_price2') . ' where subscriber_id=' . $subid . ' and pincode = ' . $zipcode . ' and category =2 and range_from=5 and range_to=8 '));
                $pk3 = DB::statement(DB::raw('update  `price` SET amount=' . $request->get('pk_price3') . ' where subscriber_id=' . $subid . ' and pincode = ' . $zipcode . ' and category =2 and range_from=8 and range_to=10 '));
                $pk4 = DB::statement(DB::raw('update  `price` SET amount=' . $request->get('pk_price4') . ' where subscriber_id=' . $subid . ' and pincode = ' . $zipcode . ' and category =2 and range_from=10 and range_to=50 '));
                //Drop and delivery

                $bd1 = DB::statement(DB::raw('update  `price` SET amount=' . $request->get('bd_price1') . ' where subscriber_id=' . $subid . ' and pincode = ' . $zipcode . ' and category =3 and range_from=0 and range_to=5 '));
                $bd2 = DB::statement(DB::raw('update  `price` SET amount=' . $request->get('bd_price2') . ' where subscriber_id=' . $subid . ' and pincode = ' . $zipcode . ' and category =3 and range_from=5 and range_to=8 '));
                $bd3 = DB::statement(DB::raw('update  `price` SET amount=' . $request->get('bd_price3') . ' where subscriber_id=' . $subid . ' and pincode = ' . $zipcode . ' and category =3 and range_from=8 and range_to=10 '));
                $bd4 = DB::statement(DB::raw('update  `price` SET amount=' . $request->get('bd_price4') . ' where subscriber_id=' . $subid . ' and pincode = ' . $zipcode . ' and category =3 and range_from=10 and range_to=50 '));

                //auto
                // $at1 = DB::statement(DB::raw('update  `price` SET amount=' . $request->get('at_price1') . ' where subscriber_id=' . $subid . ' and pincode = ' . $zipcode . ' and category =4 and range_from=0 and range_to=5 '));
                // $at2 = DB::statement(DB::raw('update  `price` SET amount=' . $request->get('at_price2') . ' where subscriber_id=' . $subid . ' and pincode = ' . $zipcode . ' and category =4 and range_from=5 and range_to=8 '));
                // $at3 = DB::statement(DB::raw('update  `price` SET amount=' . $request->get('at_price3') . ' where subscriber_id=' . $subid . ' and pincode = ' . $zipcode . ' and category =4 and range_from=8 and range_to=10 '));
                // $at4 = DB::statement(DB::raw('update  `price` SET amount=' . $request->get('at_price4') . ' where subscriber_id=' . $subid . ' and pincode = ' . $zipcode . ' and category =4 and range_from=10 and range_to=50 '));
                $auto = [
                    ['range_from' => 0, 'range_to' => 5, 'amount' => $request->get('at_price1')],
                    ['range_from' => 5, 'range_to' => 8, 'amount' => $request->get('at_price2')],
                    ['range_from' => 8, 'range_to' => 10, 'amount' => $request->get('at_price3')],
                    ['range_from' => 10, 'range_to' => 50, 'amount' => $request->get('at_price4')]
                ];

                foreach ($auto as $item) {
                    DB::table('price')->updateOrInsert(
                        [
                            'subscriber_id' => $subid,
                            'pincode' => $zipcode,
                            'category' => 4,
                            'range_from' => $item['range_from'],
                            'range_to' => $item['range_to']
                        ],
                        ['amount' => $item['amount']]
                    );
                }

                $cab = [
                    ['range_from' => 0, 'range_to' => 5, 'amount' => $request->get('cab_price1')],
                    ['range_from' => 5, 'range_to' => 8, 'amount' => $request->get('cab_price2')],
                    ['range_from' => 8, 'range_to' => 10, 'amount' => $request->get('cab_price3')],
                    ['range_from' => 10, 'range_to' => 50, 'amount' => $request->get('cab_price4')]
                ];

                foreach ($cab as $item) {
                    DB::table('price')->updateOrInsert(
                        [
                            'subscriber_id' => $subid,
                            'pincode' => $zipcode,
                            'category' => 5,
                            'range_from' => $item['range_from'],
                            'range_to' => $item['range_to']
                        ],
                        ['amount' => $item['amount']]
                    );
                }
            }
        }
    }

    public function deletesubscribervideo(Request $d)
    {
        DB::statement(DB::raw("update  `subscriber` SET video='' where id='$d->id'"));
        return redirect()->back();
    }


    public function block(Request $request, $id)
    {
        $subscriber = Subscriber::findorFail($id);

        $subscriber->blockedstatus = '0';
        $subscriber->update();
        $drivers = Driver::where('subscriberId', $subscriber->id)->get();
        foreach ($drivers as $driver) {
            $driver->status = 2;
            $driver->update();
        }
        $block = new Blocklist();
        $block->table = 'subscriber';
        $block->blockedId = $id;
        $block->blockedBy = Auth::id();
        $block->comments = $request->get('reason');
        $block->save();

        $this->sendSubscriberStatusWhatsapp($subscriber, 'banned');

        return redirect('subscriberList')->with('success', 'Subscriber blocked ');
    }
    public function subscriberunblock(Request $request)
    {
        $subscriberid = $request->get('sub_id');
        $Reason = $request->get('comments');
        $subscriber = Subscriber::findorFail($subscriberid);
        $subscriber->blockedstatus = 1;
        $subscriber->update();
        $drivers = Driver::where('subscriberId', $subscriber->id)->get();
        foreach ($drivers as $driver) {
            $driver->status = 1;
            $driver->update();
        }
        $unblock = new Unblocklist();
        $unblock->table = 'subscriber';
        $unblock->unblockedId = $subscriberid;
        $unblock->unblockedBy = Auth::id();
        $unblock->comments = $request->get('comments');
        $unblock->save();
        return redirect('subscriberList')->with('success', 'Subscriber unblocked ');
    }

    private function sendSubscriberStatusWhatsapp(Subscriber $subscriber, string $status): void
    {
        $templateName = $status === 'banned'
            ? env('WHATSAPP_SUBSCRIBER_BANNED_TEMPLATE')
            : env('WHATSAPP_SUBSCRIBER_INACTIVE_TEMPLATE');

        if (!$templateName) {
            Log::info('Subscriber status WhatsApp template not configured yet.', [
                'subscriber_id' => $subscriber->id,
                'status' => $status,
            ]);
            return;
        }

        $token = env('WACTO_WHATSAPP_TOKEN');
        if (!$token) {
            Log::warning('WACTO_WHATSAPP_TOKEN is not configured.');
            return;
        }

        $payload = [
            'campaignId' => env('WACTO_WHATSAPP_CAMPAIGN_ID', '101'),
            'to' => $subscriber->mobile,
            'type' => 'template',
            'template' => [
                'language' => [
                    'policy' => 'deterministic',
                    'code' => 'en',
                ],
                'name' => $templateName,
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            ['type' => 'text', 'text' => $subscriber->name],
                            ['type' => 'text', 'text' => $subscriber->subscriberId],
                        ],
                    ],
                ],
            ],
        ];

        $ch = curl_init('http://backend.wacto.ai/v1/message/send-message?token=' . $token);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($httpCode !== 200) {
            Log::warning('Subscriber status WhatsApp failed.', compact('httpCode', 'response', 'error'));
        }
    }
}
