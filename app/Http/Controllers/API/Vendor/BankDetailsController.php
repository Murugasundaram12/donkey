<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BankDetailsController extends Controller
{
    /**
     * Get Vendor Bank Details
     */
    public function show(Request $request)
    {
        $vendor = $request->user();

        return response()->json([
            'status' => true,
            'message' => 'Bank details retrieved successfully',
            'data' => [
                'bank_account_number' => (string) ($vendor->bankacno ?? ''),
                'ifsc_code' => (string) ($vendor->ifsccode ?? ''),
                'account_type' => (string) ($vendor->account_type ?? 'Savings'),
                'bank_statement_url' => $vendor->bankstatement ? asset('public/subscriber/bank/' . $vendor->bankstatement) : null,
            ]
        ]);
    }

    /**
     * Update Vendor Bank Details
     */
    public function update(Request $request)
    {
        $vendor = $request->user();

        $validator = Validator::make($request->all(), [
            'bank_account_number' => 'nullable|string|max:50',
            'ifsc_code' => 'nullable|string|max:20',
            'account_type' => 'nullable|string|max:50',
            'bankstatement' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->filled('bank_account_number')) {
            $vendor->bankacno = $request->bank_account_number;
        }
        if ($request->filled('ifsc_code')) {
            $vendor->ifsccode = $request->ifsc_code;
        }
        if ($request->filled('account_type')) {
            $vendor->account_type = $request->account_type;
        }

        if ($request->hasFile('bankstatement')) {
            $fileName = time() . '_bank_' . uniqid() . '.' . $request->bankstatement->extension();
            $request->bankstatement->move(public_path('subscriber/bank'), $fileName);
            $vendor->bankstatement = $fileName;
        }

        $vendor->save();

        return response()->json([
            'status' => true,
            'message' => 'Bank details updated successfully',
            'data' => [
                'bank_account_number' => (string) ($vendor->bankacno ?? ''),
                'ifsc_code' => (string) ($vendor->ifsccode ?? ''),
                'account_type' => (string) ($vendor->account_type ?? ''),
                'bank_statement_url' => $vendor->bankstatement ? asset('public/subscriber/bank/' . $vendor->bankstatement) : null,
            ]
        ]);
    }
}
