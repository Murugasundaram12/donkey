<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\Pincode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Helper to retrieve base scoped booking query for current vendor
     */
    private function getVendorBookingQuery($vendor)
    {
        $subscribersPin = json_decode($vendor->pincode, true);
        $subscribersPin = is_array($subscribersPin) ? array_values($subscribersPin) : [];
        $pincodes = Pincode::whereIn('id', $subscribersPin)->pluck('pincode')->toArray();

        return Booking::with(['user', 'bookingPayment', 'pincode'])
            ->where(function ($query) use ($vendor, $pincodes) {
                $query->where('assigned_subscriber_id', $vendor->id)
                      ->orWhere('provider_accepted_by', $vendor->id);
                if (!empty($pincodes)) {
                    $query->orWhere(function ($available) use ($pincodes) {
                        $available->whereNull('assigned_subscriber_id')
                            ->whereNull('provider_accepted_by')
                            ->whereIn('pincode', $pincodes);
                    });
                }
            });
    }

    /**
     * Today's Bookings
     */
    public function today(Request $request)
    {
        $vendor = $request->user();
        $query = $this->getVendorBookingQuery($vendor)
            ->whereDate('created_at', Carbon::today());

        if ($request->has('status') && $request->status !== null) {
            $query->where('status', (int) $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_id', 'like', "%{$search}%")
                  ->orWhere('customer_id', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->get('per_page', 15);
        $bookings = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'status' => true,
            'message' => 'Today\'s bookings retrieved successfully',
            'data' => $this->formatPaginatedBookings($bookings)
        ]);
    }

    /**
     * Incomplete Bookings (status pending 0 or in-progress 1)
     */
    public function incomplete(Request $request)
    {
        $vendor = $request->user();
        $query = $this->getVendorBookingQuery($vendor)
            ->whereIn('status', [0, 1]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_id', 'like', "%{$search}%")
                  ->orWhere('customer_id', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->get('per_page', 15);
        $bookings = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'status' => true,
            'message' => 'Incomplete bookings retrieved successfully',
            'data' => $this->formatPaginatedBookings($bookings)
        ]);
    }

    /**
     * All Vendor Bookings
     */
    public function index(Request $request)
    {
        $vendor = $request->user();
        $query = $this->getVendorBookingQuery($vendor);

        if ($request->has('status') && $request->status !== null && $request->status !== '') {
            $query->where('status', (int) $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_id', 'like', "%{$search}%")
                  ->orWhere('customer_id', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->get('per_page', 15);
        $bookings = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'status' => true,
            'message' => 'Bookings retrieved successfully',
            'data' => $this->formatPaginatedBookings($bookings)
        ]);
    }

    /**
     * Single Booking Detail
     */
    public function show(Request $request, $id)
    {
        $vendor = $request->user();
        $booking = $this->getVendorBookingQuery($vendor)
            ->where(function ($q) use ($id) {
                $q->where('id', $id)->orWhere('booking_id', $id);
            })
            ->first();

        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Booking not found or access denied.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Booking details retrieved successfully',
            'data' => [
                'booking' => $this->formatBookingDetail($booking)
            ]
        ]);
    }

    /**
     * Vendor Accepts Booking
     */
    public function accept(Request $request, $id)
    {
        $vendor = $request->user();
        $booking = $this->getVendorBookingQuery($vendor)
            ->where(function ($q) use ($id) {
                $q->where('id', $id)->orWhere('booking_id', $id);
            })
            ->first();

        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Booking not found or access denied.'
            ], 404);
        }

        if ((int) $booking->status === 3) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot accept a cancelled booking.'
            ], 400);
        }

        $booking->provider_accepted_by = $vendor->id;
        $booking->provider_accepted_at = Carbon::now();
        $booking->assigned_subscriber_id = $vendor->id;
        if ((int) $booking->status === 0) {
            $booking->status = 1;
        }
        $booking->save();

        return response()->json([
            'status' => true,
            'message' => 'Booking accepted successfully',
            'data' => [
                'booking' => $this->formatBookingDetail($booking->fresh())
            ]
        ]);
    }

    /**
     * Vendor Rejects / Cancels Booking
     */
    public function reject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $vendor = $request->user();
        $booking = $this->getVendorBookingQuery($vendor)
            ->where(function ($q) use ($id) {
                $q->where('id', $id)->orWhere('booking_id', $id);
            })
            ->first();

        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Booking not found or access denied.'
            ], 404);
        }

        if ((int) $booking->status === 2) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot reject a completed booking.'
            ], 400);
        }

        $booking->status = 3;
        $booking->cancelledby = 'vendor';
        $booking->reason = $request->input('reason', 'Rejected by vendor');
        $booking->save();

        return response()->json([
            'status' => true,
            'message' => 'Booking rejected successfully',
            'data' => [
                'booking' => $this->formatBookingDetail($booking->fresh())
            ]
        ]);
    }

    /**
     * Update Booking Status
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:0,1,2,3',
            'reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $vendor = $request->user();
        $booking = $this->getVendorBookingQuery($vendor)
            ->where(function ($q) use ($id) {
                $q->where('id', $id)->orWhere('booking_id', $id);
            })
            ->first();

        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Booking not found or access denied.'
            ], 404);
        }

        $newStatus = (int) $request->status;
        $currentStatus = (int) $booking->status;

        // Prevent invalid transitions
        if ($currentStatus === 2 && $newStatus !== 2) {
            return response()->json([
                'status' => false,
                'message' => 'Completed bookings cannot change status.'
            ], 400);
        }

        if ($currentStatus === 3 && $newStatus !== 3) {
            return response()->json([
                'status' => false,
                'message' => 'Cancelled bookings cannot change status.'
            ], 400);
        }

        $booking->status = $newStatus;
        if ($newStatus === 3) {
            $booking->cancelledby = 'vendor';
            if ($request->filled('reason')) {
                $booking->reason = $request->reason;
            }
        }
        $booking->save();

        return response()->json([
            'status' => true,
            'message' => 'Booking status updated successfully',
            'data' => [
                'booking' => $this->formatBookingDetail($booking->fresh())
            ]
        ]);
    }

    /**
     * Assign Rider to Booking
     */
    public function assignRider(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $vendor = $request->user();
        $booking = $this->getVendorBookingQuery($vendor)
            ->where(function ($q) use ($id) {
                $q->where('id', $id)->orWhere('booking_id', $id);
            })
            ->first();

        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Booking not found or access denied.'
            ], 404);
        }

        $driverInput = $request->driver_id;
        $driver = Driver::where('subscriberId', $vendor->id)
            ->where(function ($q) use ($driverInput) {
                $q->where('id', $driverInput)
                  ->orWhere('userid', $driverInput);
            })
            ->first();

        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'Rider not found or does not belong to your account.'
            ], 404);
        }

        $booking->accepted = $driver->userid ?: $driver->id;
        $booking->driver_id = (string) ($driver->userid ?: $driver->id);
        $booking->provider_accepted_by = $vendor->id;
        $booking->provider_accepted_at = Carbon::now();
        $booking->assigned_subscriber_id = $vendor->id;
        if ((int) $booking->status === 0) {
            $booking->status = 1;
        }
        $booking->save();

        return response()->json([
            'status' => true,
            'message' => 'Rider assigned to booking successfully',
            'data' => [
                'booking' => $this->formatBookingDetail($booking->fresh())
            ]
        ]);
    }

    /**
     * Format Paginated Bookings Response
     */
    private function formatPaginatedBookings($paginator)
    {
        $items = collect($paginator->items())->map(function ($booking) {
            return $this->formatBookingDetail($booking);
        });

        return [
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
            'items' => $items,
        ];
    }

    /**
     * Format Single Booking Record
     */
    private function formatBookingDetail($booking): array
    {
        $payment = $booking->bookingPayment->first();
        $customer = $booking->user;

        $statusText = match ((int) $booking->status) {
            0 => 'Pending',
            1 => 'In Progress / Accepted',
            2 => 'Completed',
            3 => 'Cancelled',
            default => 'Unknown',
        };

        return [
            'id' => (int) $booking->id,
            'booking_id' => (string) $booking->booking_id,
            'title' => (string) ($booking->title ?? 'Delivery Service'),
            'content' => (string) ($booking->content ?? ''),
            'status' => (int) $booking->status,
            'status_text' => $statusText,
            'category' => (int) $booking->category,
            'distance' => (float) $booking->distance,
            'duration' => (string) ($booking->duration ?? ''),
            'pincode' => (string) ($booking->pincode ?? ''),
            'otp' => (string) ($booking->otp ?? ''),
            'reason' => (string) ($booking->reason ?? ''),
            'cancelled_by' => (string) ($booking->cancelledby ?? ''),
            'created_at' => $booking->created_at ? $booking->created_at->toDateTimeString() : null,
            'updated_at' => $booking->updated_at ? $booking->updated_at->toDateTimeString() : null,
            'customer' => $customer ? [
                'user_id' => (string) $customer->user_id,
                'name' => (string) ($customer->name ?: ($customer->firstname . ' ' . $customer->lastname)),
                'phone' => (string) ($customer->phone ?? ''),
                'email' => (string) ($customer->email ?? ''),
            ] : [
                'user_id' => (string) ($booking->customer_id ?? ''),
                'name' => (string) ($booking->external_name ?? 'Customer'),
                'phone' => (string) ($booking->external_phone ?? ''),
                'email' => '',
            ],
            'rider_id' => (string) ($booking->accepted ?? $booking->driver_id ?? ''),
            'payment' => $payment ? [
                'total' => (float) $payment->total,
                'base_price' => (float) $payment->base_price,
                'service_cost' => (float) $payment->service_cost,
                'coupon_amount' => (float) $payment->coupon_amount,
                'status' => (int) $payment->status,
                'type' => (string) $payment->type,
            ] : null,
        ];
    }
}
