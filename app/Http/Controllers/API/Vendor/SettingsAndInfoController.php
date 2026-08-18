<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsAndInfoController extends Controller
{
    /**
     * Get Vendor Notification & Account Settings
     */
    public function getSettings(Request $request)
    {
        $vendor = $request->user();

        $settings = json_decode($vendor->notification_settings ?? '{}', true) ?: [
            'push_notifications' => true,
            'email_alerts' => true,
            'sms_alerts' => true,
            'new_booking_sound' => true,
        ];

        return response()->json([
            'status' => true,
            'message' => 'Settings retrieved successfully',
            'data' => [
                'notification_settings' => $settings
            ]
        ]);
    }

    /**
     * Update Vendor Settings
     */
    public function updateSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notification_settings' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $vendor = $request->user();
        $vendor->notification_settings = json_encode($request->notification_settings);
        $vendor->save();

        return response()->json([
            'status' => true,
            'message' => 'Settings updated successfully',
            'data' => [
                'notification_settings' => $request->notification_settings
            ]
        ]);
    }

    /**
     * Support Contact Details
     */
    public function support(Request $request)
    {
        $site = site::first();

        return response()->json([
            'status' => true,
            'message' => 'Support information retrieved successfully',
            'data' => [
                'support_email' => (string) ($site->email ?? 'support@donkeydeliveries.com'),
                'support_phone' => (string) ($site->phone ?? '+91-9000000000'),
                'helpline' => (string) ($site->phone ?? '+91-9000000000'),
                'website' => (string) ($site->website ?? 'https://donkeydeliveries.com'),
                'address' => (string) ($site->address ?? 'Donkey Deliveries HQ'),
            ]
        ]);
    }

    /**
     * Terms and Conditions
     */
    public function terms(Request $request)
    {
        $site = site::first();

        return response()->json([
            'status' => true,
            'message' => 'Terms and Conditions retrieved successfully',
            'data' => [
                'title' => 'Terms & Conditions',
                'content' => (string) ($site->tc ?? 'Welcome to Donkey Deliveries Vendor Application. By using our platform, you agree to comply with our service standards, terms of service, and vendor policies.'),
            ]
        ]);
    }

    /**
     * Privacy Policy
     */
    public function privacy(Request $request)
    {
        $site = site::first();

        return response()->json([
            'status' => true,
            'message' => 'Privacy Policy retrieved successfully',
            'data' => [
                'title' => 'Privacy Policy',
                'content' => (string) ($site->privacy_policy ?? 'Donkey Deliveries respects your privacy. We collect and protect vendor information strictly for account management, service delivery, and legal compliance.'),
            ]
        ]);
    }

    /**
     * About Information
     */
    public function about(Request $request)
    {
        $site = site::first();

        return response()->json([
            'status' => true,
            'message' => 'About information retrieved successfully',
            'data' => [
                'app_name' => 'Donkey Vendor App',
                'version' => '1.0.0',
                'company_name' => 'Donkey Deliveries',
                'description' => 'Official vendor partner application for Donkey Deliveries logistics and order management.',
            ]
        ]);
    }
}
