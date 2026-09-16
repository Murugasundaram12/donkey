<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\PaymentDetails;
use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Get Authenticated Vendor Payment Transactions List
     */
    public function index(Request $request)
    {
        $vendor = $request->user();

        // Vendor ownership is determined via Subscriber->subscriberId in payment_details.
        // Fallback to vendor->id ensures robustness if subscriberId is ever numeric or mapped to id.
        $subscriberIdValues = array_filter([
            (string) $vendor->subscriberId,
            (string) $vendor->id,
        ]);

        $query = PaymentDetails::whereIn('subscriberId', $subscriberIdValues);

        if ($request->filled('status')) {
            $statusParam = strtolower((string) $request->status);
            if ($statusParam === 'success' || $statusParam === '200') {
                $query->where('status_code', '200');
            } elseif ($statusParam === 'failed' || $statusParam === 'failure') {
                $query->where('status_code', '!=', '200');
            } else {
                $query->where('status_code', $request->status);
            }
        }

        if ($request->filled('type')) {
            $query->where('type', (int) $request->type);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('payment_id', 'like', "%{$search}%")
                  ->orWhere('invoice_no', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->get('per_page', 15);
        if ($perPage <= 0 || $perPage > 100) {
            $perPage = 15;
        }

        $payments = $query->orderBy('created_at', 'desc')->orderBy('id', 'desc')->paginate($perPage);

        $formatted = collect($payments->items())->map(function ($p) use ($vendor) {
            return $this->formatPayment($p, $vendor);
        });

        return response()->json([
            'status' => true,
            'success' => true,
            'message' => 'Payments retrieved successfully',
            'data' => [
                'payments' => $formatted,
                'current_page' => $payments->currentPage(),
                'per_page' => $payments->perPage(),
                'total' => $payments->total(),
                'last_page' => $payments->lastPage(),
            ]
        ]);
    }

    /**
     * Single Payment Detail
     */
    public function show(Request $request, $id)
    {
        $vendor = $request->user();

        $subscriberIdValues = array_filter([
            (string) $vendor->subscriberId,
            (string) $vendor->id,
        ]);

        $payment = PaymentDetails::whereIn('subscriberId', $subscriberIdValues)
            ->where(function ($q) use ($id) {
                if (is_numeric($id)) {
                    $q->where('id', (int) $id);
                } else {
                    $q->where('payment_id', $id)->orWhere('invoice_no', $id);
                }
            })
            ->first();

        if (!$payment) {
            return response()->json([
                'status' => false,
                'success' => false,
                'message' => 'Payment record not found or access denied.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'success' => true,
            'message' => 'Payment details retrieved successfully',
            'data' => [
                'payment' => $this->formatPayment($payment, $vendor)
            ]
        ]);
    }

    /**
     * Current Subscription Renewal Payment Details for Authenticated Vendor
     */
    public function subscriptionPayment(Request $request)
    {
        $vendor = $request->user();

        // 1. Determine base subscription price strictly from subscriber.subscription_price via shared helper
        $pricing = Subscriber::calculateSubscriptionPricing($vendor);

        // 2. Determine current payment validity / status
        $paymentStatus = 1;
        if (isset($vendor->blockedstatus) && (int) $vendor->blockedstatus === 0) {
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

        return response()->json([
            'status' => true,
            'success' => true,
            'message' => 'Subscription payment details retrieved successfully',
            'data' => [
                'payment_type' => 'Subscription',
                'subscription_price' => $pricing['price'],
                'gst_percentage' => $pricing['gst_percentage'],
                'gst_amount' => $pricing['gst_amount'],
                'total_payable' => $pricing['total_payable'],
                'total_payable_in_paise' => $pricing['total_payable_in_paise'],
                'currency' => 'INR',
                'payment_status' => (int) $paymentStatus,
                'expiry_date' => $vendor->expiryDate ? Carbon::parse($vendor->expiryDate)->format('Y-m-d') : null,
                'need_to_pay' => (int) ($vendor->need_to_pay ?? 0),
                'platform_fee' => (float) ($vendor->platform_fee ?? 0),
            ]
        ]);
    }

    private function formatPayment(PaymentDetails $p, $vendor = null): array
    {
        // Amount in payment_details is stored in paise (currency subunit) from Razorpay
        $amount = is_numeric($p->amount) ? round(((float) $p->amount) / 100, 2) : 0.0;
        $isSuccess = ($p->status_code == '200' || (int) $p->status_code === 200);

        $planName = match ((int) $p->type) {
            1 => 'Subscription',
            2 => 'Custom Subscription',
            3 => 'Platform Fee',
            default => 'Subscription',
        };

        return [
            'id' => (int) $p->id,
            'amount' => $amount,
            'amount_in_paise' => is_numeric($p->amount) ? (int) $p->amount : 0,
            'amount_formatted' => '₹' . number_format($amount, 2),
            'payment_method' => 'Razorpay',
            'transaction_id' => (string) ($p->payment_id ?? ''),
            'payment_id' => (string) ($p->payment_id ?? ''),
            'status' => $isSuccess ? 'success' : 'failed',
            'status_code' => (string) ($p->status_code ?? ''),
            'status_text' => $isSuccess ? 'Success' : 'Failed',
            'plan' => $planName,
            'type' => (int) $p->type,
            'type_name' => $planName,
            'invoice_no' => (string) ($p->invoice_no ?? ''),
            'invoice_url' => $p->id ? url('/invoicedownloadPDF/' . $p->id) : null,
            'paid_at' => $p->created_at ? Carbon::parse($p->created_at)->toDateTimeString() : null,
            'created_at' => $p->created_at ? Carbon::parse($p->created_at)->toDateTimeString() : null,
        ];
    }
}
