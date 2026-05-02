<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
    
    public function generate_referral_code()
    {
        User::whereNull('referral_code')
            ->orwhere('referral_code', '')
            ->chunk(500, function ($users) {

                foreach ($users as $user) {

                    do {
                        // Generate a random 4–6 digit number
                        $randomNumber = rand(1000, 999999);

                        // Combine prefix with number
                        $referralCode = 'DNK#' . $randomNumber;

                        // Check if the referral code already exists
                        $exists = User::where('referral_code', $referralCode)->exists();
                    } while ($exists);

                    $user->referral_code = $referralCode;
                    $user->save();
                }
            });
            
        return response()->json([
            'status' => true,
            'message' => 'Referral Codes Generated Successfully'
        ]);

        //dd("Generated Successfully");
    }    
}
