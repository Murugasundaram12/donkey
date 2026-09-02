<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Vendor Login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required_without_all:email,mobile',
            'email' => 'nullable|string',
            'mobile' => 'nullable|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $loginInput = $request->input('login') ?: ($request->input('email') ?: $request->input('mobile'));
        $password = $request->input('password');

        $vendor = Subscriber::where('email', $loginInput)
            ->orWhere('mobile', $loginInput)
            ->orWhere('subscriberId', $loginInput)
            ->first();

        if (!$vendor) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials.'
            ], 401);
        }

        // Verify password (support both Hash and legacy plain text check)
        $passwordMatches = Hash::check($password, $vendor->password) || ($vendor->password === $password);
        if (!$passwordMatches) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials.'
            ], 401);
        }

        if ($request->filled('device_token')) {
            $vendor->device_token = $request->input('device_token');
            $vendor->save();
        }

        // Check blocked status
        if (isset($vendor->blockedstatus) && (int)$vendor->blockedstatus === 0) {
            return response()->json([
                'status' => false,
                'message' => 'Your account has been blocked. Please contact admin.'
            ], 403);
        }

        // Generate Sanctum access token (allowed even if subscription expired so vendor can access renewal APIs)
        $token = $vendor->createToken('vendor_app_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'vendor' => $this->formatVendorData($vendor)
            ]
        ], 200);
    }

    /**
     * OTP Verification
     */
    public function otpVerify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|string',
            'otp' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $vendor = Subscriber::where('mobile', $request->mobile)->first();

        if (!$vendor) {
            return response()->json([
                'status' => false,
                'message' => 'Vendor not found.'
            ], 440);
        }

        // Check OTP (support demo OTP 1234 or matching vendor notify/otp if set)
        $otp = $request->otp;
        if ($otp !== '1234' && $vendor->notify !== $otp) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP.'
            ], 400);
        }

        $token = $vendor->createToken('vendor_app_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'OTP verified successfully',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'vendor' => $this->formatVendorData($vendor)
            ]
        ]);
    }

    /**
     * OTP Resend
     */
    public function otpResend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $vendor = Subscriber::where('mobile', $request->mobile)->first();

        if (!$vendor) {
            return response()->json([
                'status' => false,
                'message' => 'Vendor not found.'
            ], 404);
        }

        $otp = (string) rand(1000, 9999);
        $vendor->notify = $otp;
        $vendor->save();

        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully',
            'data' => [
                'mobile' => $vendor->mobile
            ]
        ]);
    }

    /**
     * Authenticated Vendor Profile
     */
    public function me(Request $request)
    {
        $vendor = $request->user();

        return response()->json([
            'status' => true,
            'message' => 'Vendor profile fetched successfully',
            'data' => [
                'vendor' => $this->formatVendorData($vendor)
            ]
        ]);
    }

    /**
     * Change Password
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $vendor = $request->user();

        $passwordMatches = Hash::check($request->current_password, $vendor->password) || ($vendor->password === $request->current_password);
        if (!$passwordMatches) {
            return response()->json([
                'status' => false,
                'message' => 'Current password does not match.'
            ], 400);
        }

        $vendor->password = Hash::make($request->new_password);
        $vendor->save();

        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully'
        ]);
    }

    /**
     * Forgot Password Request
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required_without:mobile|nullable|email',
            'mobile' => 'required_without:email|nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = Subscriber::query();
        if ($request->filled('email')) {
            $query->where('email', $request->email);
        } else {
            $query->where('mobile', $request->mobile);
        }

        $vendor = $query->first();

        if (!$vendor) {
            return response()->json([
                'status' => false,
                'message' => 'Vendor account not found.'
            ], 404);
        }

        $otp = (string) rand(1000, 9999);
        $vendor->notify = $otp;
        $vendor->save();

        return response()->json([
            'status' => true,
            'message' => 'Password reset request initiated. OTP sent.',
            'data' => [
                'mobile' => $vendor->mobile,
                'email' => $vendor->email
            ]
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user && method_exists($user, 'currentAccessToken') && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Format vendor output structure
     */
    private function formatVendorData(Subscriber $vendor): array
    {
        $pincodes = json_decode($vendor->pincode, true);
        $pincodes = is_array($pincodes) ? array_values($pincodes) : [];

        $paymentStatus = 1;
        if (isset($vendor->blockedstatus) && (int)$vendor->blockedstatus === 0) {
            $paymentStatus = 0;
        } elseif (!empty($vendor->expiryDate)) {
            try {
                $paymentStatus = Carbon::parse($vendor->expiryDate)->endOfDay()->isPast() ? 0 : 1;
            } catch (\Throwable $e) {
                $paymentStatus = (int) ($vendor->status ?? 1);
            }
        } else {
            $paymentStatus = (int) ($vendor->status ?? 1);
        }

        return [
            'id' => (int) $vendor->id,
            'subscriber_id' => (string) $vendor->subscriberId,
            'name' => (string) $vendor->name,
            'email' => (string) $vendor->email,
            'mobile' => (string) $vendor->mobile,
            'location' => (string) ($vendor->location ?? ''),
            'pincode' => $pincodes,
            'status' => (int) $vendor->status,
            'blocked_status' => (int) $vendor->blockedstatus,
            'payment_status' => (int) $paymentStatus,
            'payment_expiry' => $vendor->expiryDate ? Carbon::parse($vendor->expiryDate)->format('Y-m-d') : null,
            'subscription_date' => $vendor->subscriptionDate ? Carbon::parse($vendor->subscriptionDate)->format('Y-m-d') : null,
            'expiry_date' => $vendor->expiryDate ? Carbon::parse($vendor->expiryDate)->format('Y-m-d') : null,
            'need_to_pay' => (float) $vendor->need_to_pay,
            'platform_fee' => (float) $vendor->platform_fee,
            'created_at' => $vendor->created_at ? $vendor->created_at->toDateTimeString() : null,
        ];
    }
}
