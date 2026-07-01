<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\Password\StorePasswordRequest;
use App\Http\Requests\Auth\Password\UpdatePasswordRequest;
use App\Http\Requests\StoreSubscriberPasswordRequest;
use App\Http\Requests\UpdateSubscriberPasswordRequest;
use App\Mail\ForgotPasswordMail;
use App\Models\Admin;
use App\Models\Employee;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function forgotPassword()
    {
        return view('admin.password.email');
    }

    public function subscriberForgotPassword()
    {
        // dd("hii");
        return view('admin.password.subscriberEmail');
    }

    public function sendForgotPasswordEmail(StorePasswordRequest $request)
    {
        $employee = Admin::where('email', $request->email)->first();
        if (!$employee) {
            return back()->withErrors(['email' => 'Email address not found.'])->withInput();
        }

        $encid = encrypt($employee->id);
        $resetLink = config('app.url') . '/passwordReset/'.$encid;
        // Send email using Laravel's mailing system
        $data = [
            'employee' => $employee,
            'resetLink' => $resetLink,
        ];
        try {
            Mail::to($employee->email)->send(new ForgotPasswordMail($data));
        } catch (\Throwable $e) {
            return back()->withErrors([
                'email' => 'Unable to send reset email. Please check SMTP credentials.',
            ])->withInput();
        }

        return back()->with('success', 'Check your mail for password reset instructions.')
            ->withInput();
    }

    public function subscriberEmailVerification(StoreSubscriberPasswordRequest $request)
    {
        $employee = Employee::where('email', $request->email)->first();
        $subscriber = null;
        $resetType = 'employee';

        if (!$employee) {
            $subscriber = Subscriber::where('email', $request->email)->first();
            $resetType = 'subscriber';
        }

        $account = $employee ?: $subscriber;
        if (!$account) {
            return back()->withErrors(['email' => 'Email address not found.'])->withInput();
        }

        $encid = encrypt([
            'type' => $resetType,
            'id' => $account->id,
        ]);
        $resetLink = config('app.url') . '/subscriberpasswordReset/'.$encid;
        // Send email using Laravel's mailing system
        $data = [
            'employee' => $account,
            'resetLink' => $resetLink,
        ];
        try {
            Mail::to($account->email)->send(new ForgotPasswordMail($data));
        } catch (\Throwable $e) {
            return back()->withErrors([
                'email' => 'Unable to send reset email. Please check SMTP credentials.',
            ])->withInput();
        }

        return back()->with('success', 'Check your mail for password reset instructions.')
            ->withInput();
    }

  public function passwordReset($id)
    {
        $userId = decrypt($id);
        return view('admin.password.confirm',['userId' => $userId]);
    }

    public function subscriberPasswordReset($id)
    {
        $payload = decrypt($id);
        $resetType = 'employee';
        $userId = $payload;

        if (is_array($payload)) {
            $resetType = $payload['type'] ?? 'employee';
            $userId = $payload['id'] ?? null;
        }

        return view('admin.password.subscriberConfirm', [
            'userId' => $userId,
            'resetType' => $resetType,
        ]);
    }

    public function newPassword(UpdatePasswordRequest $request)
    {
        if ($request->password == $request->confirmed_password) {
            $validator = $request->validated();
            // dd($validator['email']);
            $employee = Admin::where('id', $validator['user_id'])->first();
            // dd($employee);
            $validator['password'] = Hash::make($request->password);
            // dd($validator);
            $employee->update([
                'password' => $validator['password']
            ]);
            return redirect()->route('login')->with('success', "Password Changed Successfully");
        } else {
            return back()->withErrors(['confirmed_password' => 'Password does not match.'])->withInput();
        }
    }

    public function SubscriberNewPassword(UpdateSubscriberPasswordRequest $request)
    {
        if ($request->password == $request->confirmed_password) {
            $validator = $request->validated();
            $resetType = $request->get('reset_type', 'employee');

            if ($resetType === 'subscriber') {
                $subscriber = Subscriber::where('id', $validator['user_id'])->first();
                if (!$subscriber) {
                    return back()->withErrors(['password' => 'Reset account not found.'])->withInput();
                }

                $subscriber->password = $validator['password'];
                $subscriber->update();

                $employee = Employee::where('email', $subscriber->email)->first();
                if ($employee) {
                    $employee->password = $validator['password'];
                    $employee->update();
                }
            } else {
                $employee = Employee::where('id', $validator['user_id'])->first();
                if (!$employee) {
                    return back()->withErrors(['password' => 'Reset account not found.'])->withInput();
                }

                $subscriber = Subscriber::where('email', $employee->email)->first();
                if ($subscriber) {
                    $subscriber->password = $validator['password'];
                    $subscriber->update();
                }

                $employee->password = $validator['password'];
                $employee->update();
            }

            return redirect()->route('subscriberLogin')->with('success', "Password Changed Successfully");
        } else {
            return back()->withErrors(['confirmed_password' => 'Password does not match.'])->withInput();
        }
    }
}
