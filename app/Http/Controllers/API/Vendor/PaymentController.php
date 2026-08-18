<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\PaymentDetails;
use App\Models\Pincode;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Get Vendor Payment Transactions List
     */
    public function index(Request $request)
    {
        $vendor = $request->user();

        $subscribersPin = json_decode($vendor->pincode, true);
        $subscribersPin = is_array($subscribersPin) ? array_values($subscribersPin) : [];
        $pincodes = Pincode::whereIn('id', $subscribersPin)->pluck('pincode')->toArray();

        // Get vendor's booking IDs
        $bookingIds = Booking::where(function ($query) use ($vendor, $pincodes) {
            $query->where('assigned_subscriber_id', $vendor->id)
                  ->orWhere('provider_accepted_by', $vendor->id);
            if (!empty($pincodes)) {
                $query->orWhereIn('pincode', $pincodes);
            }
        })->pluck('booking_id')->toArray();

        // Fetch payments for vendor's bookings
        $query = BookingPayment::whereIn('booking_id', $bookingIds);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->has('status') && $request->status !== null && $request->status !== '') {
            $query->where('status', (int) $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_id', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->get('per_page', 15);
        $payments = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // Calculate summary
        $totalAmount = (clone $query)->sum('total');

        $formatted = collect($payments->items())->map(function ($p) {
            return $this->formatPayment($p);
        });

        return response()->json([
            'status' => true,
            'message' => 'Payments retrieved successfully',
            'data' => [
                'total_amount' => (float) round($totalAmount, 2),
                'current_page' => $payments->currentPage(),
                'per_page' => $payments->perPage(),
                'total' => $payments->total(),
                'last_page' => $payments->lastPage(),
                'items' => $formatted,
            ]
        ]);
    }

    /**
     * Single Payment Detail
     */
    public function show(Request $request, $id)
    {
        $vendor = $request->user();

        $subscribersPin = json_decode($vendor->pincode, true);
        $subscribersPin = is_array($subscribersPin) ? array_values($subscribersPin) : [];
        $pincodes = Pincode::whereIn('id', $subscribersPin)->pluck('pincode')->toArray();

        $bookingIds = Booking::where(function ($query) use ($vendor, $pincodes) {
            $query->where('assigned_subscriber_id', $vendor->id)
                  ->orWhere('provider_accepted_by', $vendor->id);
            if (!empty($pincodes)) {
                $query->orWhereIn('pincode', $pincodes);
            }
        })->pluck('booking_id')->toArray();

        $payment = BookingPayment::whereIn('booking_id', $bookingIds)
            ->where('id', $id)
            ->first();

        if (!$payment) {
            return response()->json([
                'status' => false,
                'message' => 'Payment record not found or access denied.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Payment details retrieved successfully',
            'data' => [
                'payment' => $this->formatPayment($payment)
            ]
        ]);
    }

    private function formatPayment($p): array
    {
        return [
            'id' => (int) $p->id,
            'booking_id' => (string) $p->booking_id,
            'transaction_id' => (string) ($p->transaction_id ?? ''),
            'order_id' => (string) ($p->order_id ?? ''),
            'total' => (float) $p->total,
            'base_price' => (float) $p->base_price,
            'tax' => (float) $p->tax,
            'service_cost' => (float) $p->service_cost,
            'coupon_amount' => (float) $p->coupon_amount,
            'status' => (int) $p->status,
            'status_text' => ((int) $p->status === 1 || (int) $p->status === 2) ? 'Completed' : 'Pending/Failed',
            'type' => (string) $p->type,
            'created_at' => $p->created_at ? Carbon::parse($p->created_at)->toDateTimeString() : null,
        ];
    }
}
