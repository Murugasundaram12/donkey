<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
  //protected $redirectTo = RouteServiceProvider::HOME;
   protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Attempt to log the user into the application across supported admin & PBP guards.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(\Illuminate\Http\Request $request)
    {
        if ($this->guard()->attempt($this->credentials($request), $request->boolean('remember'))) {
            return true;
        }

        if (\Illuminate\Support\Facades\Auth::guard('subscriber')->attempt($this->credentials($request), $request->boolean('remember'))) {
            \Illuminate\Support\Facades\Session::put('subscribers', \Illuminate\Support\Facades\Auth::guard('subscriber')->user()->fresh());
            return true;
        }

        if (\Illuminate\Support\Facades\Auth::guard('employee')->attempt($this->credentials($request), $request->boolean('remember'))) {
            \Illuminate\Support\Facades\Session::put('subscribers', \Illuminate\Support\Facades\Auth::guard('employee')->user()->fresh());
            return true;
        }

        return false;
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        if (\Illuminate\Support\Facades\Auth::guard('subscriber')->check() || \Illuminate\Support\Facades\Auth::guard('employee')->check()) {
            return redirect()->route('subscribers.dashboard');
        }

        if ($user && method_exists($user, 'hasAnyRole') && $user->hasAnyRole(['Subscriber Admin', 'Sub Employee'])) {
            return redirect()->route('subscribers.dashboard');
        }

        return redirect()->intended($this->redirectPath());
    }
    
     public function logout()
    {
        $this->guard()->logout();

        // Customize the redirect URL after logout
        return redirect('https://www.donkeydeliveries.com/');
    }
}
