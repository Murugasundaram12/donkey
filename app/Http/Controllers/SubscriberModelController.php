<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\site;
use Illuminate\Http\Request;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\statusnotify;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use App\Services\SubscriptionRenewalService;
use Validator;


class SubscriberModelController extends Controller
{

    public function index()
    {
        if (Session::has('subscribers') || Auth::guard('subscriber')->check() || Auth::guard('employee')->check()) {
            if (!Session::has('subscribers')) {
                Session::put('subscribers', Auth::guard('subscriber')->user() ?: Auth::guard('employee')->user());
            }

            return redirect()->route('subscribers.dashboard');
        }

        $maintainance = site::where('id', 1)->first()->maintainance;
        if ($maintainance == 1) {
            return view('auth.maintainance');
        }
        return view('subscriber.login');
    }

    public function subscriberlogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        $email = trim((string) $request->input('email'));
        $password = (string) $request->input('password');

        $subscriber = Subscriber::where('email', $email)->first();
        $employee = Employee::where('email', $email)->first();

        $storedSubscriberPassword = $subscriber ? (string) $subscriber->password : '';
        $isSubscriberBcrypt = $subscriber && Hash::check($password, $storedSubscriberPassword);
        $isSubscriberPlaintext = $subscriber && !$isSubscriberBcrypt && ($storedSubscriberPassword === $password);
        $subscriberPasswordMatches = $isSubscriberBcrypt || $isSubscriberPlaintext;

        if ($subscriber && $subscriberPasswordMatches) {
            if ((int) ($subscriber->blockedstatus ?? 1) === 0) {
                return back()->with('error', 'You have been  blocked.Please Contact admin!');
            }

            if ((int) ($subscriber->status ?? 1) === 0 || (int) ($subscriber->activestatus ?? 1) === 0) {
                return back()->with('error', 'Your Subscriber Admin not activated.Please Contact your admin!');
            }

            if ($isSubscriberPlaintext) {
                try {
                    $newPassword = Hash::make($password);
                    $affected = Subscriber::where('id', $subscriber->id)
                        ->where('password', $storedSubscriberPassword)
                        ->update(['password' => $newPassword]);

                    if ($affected === 1) {
                        $subscriber->password = $newPassword;
                        $subscriber->syncOriginalAttribute('password');
                    } else {
                        $reloadedSubscriber = Subscriber::where('id', $subscriber->id)->first();
                        if (!$reloadedSubscriber || !Hash::check($password, (string) $reloadedSubscriber->password)) {
                            \Illuminate\Support\Facades\Log::warning("Subscriber web password migration reload verification failed for Subscriber ID {$subscriber->id}: Operation=reload_verify");
                            return back()->with('error', 'Authentication service temporarily unavailable. Please try again.');
                        }

                        $subscriber->password = $reloadedSubscriber->password;
                        $subscriber->syncOriginalAttribute('password');
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error("Subscriber web password migration failed for Subscriber ID {$subscriber->id}: Operation=hash_and_update, Exception=" . get_class($e));
                    return back()->with('error', 'Authentication service temporarily unavailable. Please try again.');
                }
            }

            $isExpired = false;
            if (!empty($subscriber->expiryDate)) {
                try {
                    $isExpired = Carbon::parse($subscriber->expiryDate, SubscriptionRenewalService::TIMEZONE)
                        ->setTimezone(SubscriptionRenewalService::TIMEZONE)
                        ->endOfDay()
                        ->isPast();
                } catch (\Throwable $e) {
                    $isExpired = false;
                }
            }

            $request->session()->regenerate();
            $this->ensureSubscriberAdminSetup($subscriber);
            $remember = $request->boolean('remember');
            Auth::guard('subscriber')->login($subscriber, $remember);
            Session::put('subscribers', $subscriber->fresh());

            if ($isExpired) {
                return redirect()->route('makePlatFormFee', ['Id' => $subscriber->id]);
            }

            return redirect()->route('subscribers.dashboard');
        }

        $employeePasswordMatches = $employee && (
            Hash::check($password, $employee->password)
            || $employee->password === $password
        );

        if ($employee && $employeePasswordMatches) {
            $parentSubscriber = Subscriber::where('id', $employee->subscriber_id)->first();
            if (!$parentSubscriber) {
                return back()->with('error', 'Wrong Credentials');
            }

            if ((int) ($parentSubscriber->blockedstatus ?? 1) === 0) {
                return back()->with('error', 'You have been  blocked.Please Contact admin!');
            }

            if ((int) ($parentSubscriber->status ?? 1) === 0 || (int) ($parentSubscriber->activestatus ?? 1) === 0) {
                return back()->with('error', 'Your Subscriber Admin not activated.Please Contact your admin!');
            }

            $isParentExpired = false;
            if (!empty($parentSubscriber->expiryDate)) {
                try {
                    $isParentExpired = Carbon::parse($parentSubscriber->expiryDate, SubscriptionRenewalService::TIMEZONE)
                        ->setTimezone(SubscriptionRenewalService::TIMEZONE)
                        ->endOfDay()
                        ->isPast();
                } catch (\Throwable $e) {
                    $isParentExpired = false;
                }
            }

            if ($isParentExpired) {
                return back()->with('error', 'Your not activated (Your Account Expiried ).Please Contact admin!');
            }

            $request->session()->regenerate();
            Session::put('subscribers', $employee);
            $remember = $request->boolean('remember');
            Auth::guard('employee')->login($employee, $remember);
            return redirect()->route('subscribers.dashboard');
        }

        return back()->with('error', 'Wrong Credentials');
    }

    private function ensureSubscriberAdminSetup(Subscriber $subscriber): void
    {
        if (!in_array((string) $subscriber->getRawOriginal('created_by'), ['', '0', 'public'], true)) {
            return;
        }

        $role = Role::where('guard_name', 'subscriber')->where('name', 'Subscriber Admin')->first();
        if ($role && !$subscriber->hasRole($role->name)) {
            $subscriber->assignRole($role->name);
        }

        Employee::firstOrCreate(
            ['email' => $subscriber->email],
            [
                'name' => $subscriber->name,
                'profile' => 'profile.jpg',
                'emp_id' => 'PBP Employee ID - SELF' . $subscriber->id,
                'official_mail' => $subscriber->email,
                'mobile' => $subscriber->mobile,
                'official_mobile' => $subscriber->mobile,
                'address' => $subscriber->description ?: $subscriber->location ?: 'N/A',
                'current_address' => $subscriber->location,
                'location' => $subscriber->location,
                'aadhar' => $subscriber->aadharNo ?: 'N/A',
                'password' => $subscriber->password,
                'subscriber_id' => $subscriber->id,
            ]
        );
    }
}
