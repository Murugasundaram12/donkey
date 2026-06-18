<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\site;
use Illuminate\Http\Request;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\statusnotify;

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
        $email = $request->email;
        $password = $request->password;

        $subscriber = Subscriber::where(['email' => $email, 'password' => $password])?->get();

        $employee = Employee::where(['email' => $email, 'password' => $password])->first();

        $subscriberCount = $subscriber->count();
        if ($subscriberCount > 0) {
            $sub = $subscriber;
            $count = count($subscriber);
            if ($count > 0) {
                foreach ($subscriber as $subscriber) {
                    $subscriberstatus = $subscriber->activestatus;
                    $subscriberblockedstatus = $subscriber->blockedstatus;
                }

                if ($count == 1) {
                    if ($subscriberblockedstatus == 1) {
                        if ($subscriberstatus == 1) {
                            $date1 = date_create($sub[0]->expiryDate);
                            $date2 = date_create(date('d-m-Y'));
                            $diff = date_diff($date2, $date1);
                            $rr = $diff->format("%R");

                            if ($rr == "-") {
                                $idd = Subscriber::where(['email' => $email, 'password' => $password])->get();
                                Subscriber::where(['email' => $email, 'password' => $password])->update(['status' => 0]);

                                $block = new statusnotify();
                                $block->datas = "Payment due";
                                $block->modifiedId = $idd[0]->id;
                                $block->modifiedBy = 'Payment Due';
                                $block->message = 'Payment due';
                                $block->save();

                                return back()->with('error', 'Your not activated (Your Account Expiried ).Please Contact admin!');
                            } else {
                                Session::put('subscribers', $subscriber);
                                $subscriber = Subscriber::where('email', $email)->first();

                                if ($subscriber && $subscriber->password == $password) {
                                    $remember = $request->boolean('remember');
                                    Auth::guard('subscriber')->login($subscriber, $remember);
                                    return redirect()->route('subscribers.dashboard');
                                }
                            }
                        } else {
                            $date1 = date_create($sub[0]->expiryDate);
                            $date2 = date_create(date('d-m-Y'));
                            $diff = date_diff($date2, $date1);
                            $rr = $diff->format("%R");

                            if ($rr == "-") {
                                $idd = Subscriber::where(['email' => $email, 'password' => $password])->get();
                                Subscriber::where(['email' => $email, 'password' => $password])->update(['status' => 0]);

                                $block = new statusnotify();
                                $block->datas = "Payment due";
                                $block->modifiedId = $idd[0]->id;
                                $block->modifiedBy = 'Payment Due';
                                $block->message = 'Payment due';
                                $block->save();

                                return back()->with('error', 'Your not activated (Your Account Expiried ).Please Contact admin!');
                            } else {
                                Session::put('subscribers', $subscriber);
                                $subscriber = Subscriber::where('email', $email)->first();

                                if ($subscriber && $subscriber->password == $password) {
                                    $remember = $request->boolean('remember');
                                    Auth::guard('subscriber')->login($subscriber, $remember);
                                    return redirect()->route('subscribers.dashboard');
                                }
                            }
                        }
                    } else {
                        return back()->with('error', 'You have been  blocked.Please Contact admin!');
                    }
                } else {
                    return back()->with('error', 'Wrong Credentials');
                }
            } else {
                return back()->with('error', 'Wrong Credentials');
            }
        } elseif (isset($employee)) {
            $employee = Employee::where(['email' => $email, 'password' => $password])->first();
            $subscriber = Subscriber::where('id', $employee->subscriber_id)->get();
            $sub = $subscriber;
            $count = count($subscriber);
            foreach ($subscriber as $subscriber) {
                $subscriberstatus = $subscriber->activestatus;
                $subscriberblockedstatus = $subscriber->blockedstatus;
            }

            if ($count == 1) {
                if ($subscriberblockedstatus == 1) {
                    if ($subscriberstatus == 1) {
                        $date1 = date_create($sub[0]->expiryDate);
                        $date2 = date_create(date('d-m-Y'));
                        $diff = date_diff($date2, $date1);
                        $rr = $diff->format("%R");

                        if ($rr == "-") {
                            $idd = Subscriber::where(['email' => $email, 'password' => $password])->get();
                            Subscriber::where(['email' => $email, 'password' => $password])->update(['status' => 0]);

                            $block = new statusnotify();
                            $block->datas = "Payment due";
                            $block->modifiedId = $idd[0]->id;
                            $block->modifiedBy = 'Payment Due';
                            $block->message = 'Payment due';
                            $block->save();

                            return back()->with('error', 'Your not activated (Your Account Expiried ).Please Contact admin!');
                        } else {
                            Session::put('subscribers', $employee);
                            $subscriber = Employee::where('email', $email)->first();

                            if ($subscriber && $subscriber->password == $password) {
                                $remember = $request->boolean('remember');
                                Auth::guard('employee')->login($subscriber, $remember);
                                return redirect()->route('subscribers.dashboard');
                            }
                        }
                    } else {
                        $date1 = date_create($sub[0]->expiryDate);
                        $date2 = date_create(date('d-m-Y'));
                        $diff = date_diff($date2, $date1);
                        $rr = $diff->format("%R");

                        if ($rr == "-") {
                            $idd = Subscriber::where(['email' => $email, 'password' => $password])->get();
                            Subscriber::where(['email' => $email, 'password' => $password])->update(['status' => 0]);

                            $block = new statusnotify();
                            $block->datas = "Payment due";
                            $block->modifiedId = $idd[0]->id;
                            $block->modifiedBy = 'Payment Due';
                            $block->message = 'Payment due';
                            $block->save();

                            return back()->with('error', 'Your not activated (Your Account Expiried ).Please Contact admin!');
                        } else {
                            return back()->with('error', 'Your Subscriber Admin not activated.Please Contact your admin!');
                        }
                    }
                } else {
                    return back()->with('error', 'You have been  blocked.Please Contact admin!');
                }
            } else {
                return back()->with('error', 'Wrong Credentials');
            }
        } else {
            return back()->with('error', 'Wrong Credentials');
        }
    }
}
