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
     * @return string
     */
    public function redirectTo()
    {
        return route('dashboard');
    }

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
     * Attempt to log the user into the application.
     * Only Admin accounts (via web guard) are permitted.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(\Illuminate\Http\Request $request)
    {
        if ($this->guard()->attempt($this->credentials($request), $request->boolean('remember'))) {
            \Illuminate\Support\Facades\Auth::guard('subscriber')->logout();
            \Illuminate\Support\Facades\Auth::guard('employee')->logout();
            \Illuminate\Support\Facades\Session::forget('subscribers');
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
        $intended = $request->session()->get('url.intended');
        if ($intended && (str_contains($intended, 'subscribers') || str_contains($intended, 'login') || str_contains($intended, 'logout'))) {
            $request->session()->forget('url.intended');
        }

        return redirect()->intended(route('dashboard'));
    }
    
    public function logout(\Illuminate\Http\Request $request)
    {
        $this->guard()->logout();
        \Illuminate\Support\Facades\Auth::guard('subscriber')->logout();
        \Illuminate\Support\Facades\Auth::guard('employee')->logout();
        \Illuminate\Support\Facades\Session::forget('subscribers');

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('https://www.donkeydeliveries.com/donkey/login');
    }
}
