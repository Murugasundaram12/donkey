<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Pincode;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InquiryController extends Controller
{
    /**
     * Handle a public website inquiry submission.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Allow aliases
        if (!$request->has('email') && $request->has('mailId')) {
            $request->merge(['email' => $request->mailId]);
        }
        if (!$request->has('inquiry_for') && $request->has('category')) {
            $request->merge(['inquiry_for' => $request->category]);
        }
        if (!$request->has('message') && $request->has('description')) {
            $request->merge(['message' => $request->description]);
        }

        // Support Priority, immediate_join, and ficon aliases
        $priorityVal = null;
        if ($request->has('Priority')) {
            $priorityVal = $request->input('Priority');
        } elseif ($request->has('priority')) {
            $priorityVal = $request->input('priority');
        } elseif ($request->has('immediate_join')) {
            $priorityVal = $request->input('immediate_join');
        } elseif ($request->has('ficon')) {
            $priorityVal = $request->input('ficon');
        }

        if ($priorityVal !== null) {
            $request->merge([
                'Priority' => $priorityVal,
                'immediate_join' => $priorityVal,
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'mobile' => ['required', 'regex:/^[6-9]\d{9}$/'],
            'area' => ['required', 'string', 'max:150'],
            'pincode' => ['required', 'digits:6'],
            'inquiry_for' => ['required', 'string', 'max:100'],
            'message' => ['nullable', 'string', 'max:1000'],
            'Priority' => ['nullable'],
            'immediate_join' => ['nullable'],
        ], [
            'mobile.regex' => 'The mobile must be a valid 10-digit Indian phone number starting with 6, 7, 8, or 9.',
            'pincode.digits' => 'The pincode must be exactly 6 digits.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $mobile = trim((string) $request->input('mobile'));

        // Prevent duplicate submissions within 15 minutes
        $recentEnquiry = Enquiry::where('mobile', $mobile)
            ->where('created_at', '>=', Carbon::now()->subMinutes(15))
            ->first();

        if ($recentEnquiry) {
            return response()->json([
                'status' => false,
                'message' => 'An inquiry with this mobile number has already been submitted recently. Please wait before submitting again.',
                'errors' => [
                    'mobile' => ['An inquiry with this mobile number has already been submitted recently. Please wait before submitting again.']
                ]
            ], 422);
        }

        // Map category
        $rawInquiryFor = (string) $request->input('inquiry_for');
        $lowerInquiryFor = strtolower($rawInquiryFor);
        if (str_contains($lowerInquiryFor, 'rider') || str_contains($lowerInquiryFor, 'driver')) {
            $category = 'Rider';
        } elseif (
            str_contains($lowerInquiryFor, 'subscriber') ||
            str_contains($lowerInquiryFor, 'service') ||
            str_contains($lowerInquiryFor, 'partner') ||
            str_contains($lowerInquiryFor, 'vendor')
        ) {
            $category = 'Subscriber';
        } else {
            $category = $rawInquiryFor;
        }

        // Handle pincode matching existing Enquiry storage logic
        $pincodeInput = trim((string) $request->input('pincode'));
        $findPincode = Pincode::where('pincode', $pincodeInput)->first()?->id;
        if ($findPincode !== null) {
            $pincodeVal = [(string) $findPincode];
        } else {
            $pincodeVal = $pincodeInput;
        }

        $immediateJoinInput = $request->input('Priority') ?? $request->input('immediate_join');
        if ($immediateJoinInput !== null) {
            if (is_bool($immediateJoinInput)) {
                $immediateJoin = $immediateJoinInput;
            } elseif (is_string($immediateJoinInput)) {
                $str = strtolower(trim($immediateJoinInput));
                if (in_array($str, ['no', 'false', '0', 'off'], true)) {
                    $immediateJoin = false;
                } elseif (in_array($str, ['yes', 'true', '1', 'on'], true)) {
                    $immediateJoin = true;
                } else {
                    $immediateJoin = filter_var($immediateJoinInput, FILTER_VALIDATE_BOOLEAN);
                }
            } elseif (is_numeric($immediateJoinInput)) {
                $immediateJoin = (int) $immediateJoinInput === 1;
            } else {
                $immediateJoin = (bool) $immediateJoinInput;
            }
        } else {
            $immediateJoin = true;
        }

        $description = (string) ($request->input('message') ?? '');

        $enquiry = Enquiry::create([
            'name' => trim((string) $request->input('name')),
            'mailId' => trim((string) $request->input('email')),
            'mobile' => $mobile,
            'area' => trim((string) $request->input('area')),
            'pincode' => $pincodeVal,
            'category' => $category,
            'description' => $description,
            'ficon' => $immediateJoin ? '1' : '0',
            'subscriberId' => 0,
            'emp_id' => 'web',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Inquiry submitted successfully. Our team will contact you shortly.',
            'data' => [
                'id' => $enquiry->id,
                'name' => $enquiry->name,
                'mobile' => $enquiry->mobile,
                'email' => $enquiry->mailId,
                'area' => $enquiry->area,
                'pincode' => $pincodeInput,
                'inquiry_for' => $category,
                'category' => $enquiry->category,
                'message' => $enquiry->description,
                'Priority' => $enquiry->ficon === '1' || $enquiry->ficon === 1,
                'immediate_join' => $enquiry->ficon === '1' || $enquiry->ficon === 1,
                'created_at' => $enquiry->created_at?->toISOString() ?? now()->toISOString(),
            ],
        ], 201);
    }
}
