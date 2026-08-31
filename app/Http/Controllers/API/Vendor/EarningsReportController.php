<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Driver;
use App\Models\Pincode;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EarningsReportController extends Controller
{
    /**
     * Get Consolidated Vendor Earnings & Reports
     */
    public function index(Request $request)
    {
        $vendor = $request->user();

        // 1. Get vendor pincodes for backup pincode matching
        $subscribersPin = json_decode($vendor->pincode, true);
        $subscribersPin = is_array($subscribersPin) ? array_values($subscribersPin) : [];
        $pincodes = !empty($subscribersPin) ? Pincode::whereIn('id', $subscribersPin)->pluck('pincode')->toArray() : [];

        // Vendor riders
        $vendorRiderIds = Driver::where('subscriberId', $vendor->id)->pluck('id')->toArray();

        // Base booking query strictly scoped to authenticated vendor
        $baseVendorQuery = Booking::query()->where(function ($query) use ($vendor, $pincodes, $vendorRiderIds) {
            $query->where('assigned_subscriber_id', $vendor->id)
                  ->orWhere('provider_accepted_by', $vendor->id);
            if (!empty($vendorRiderIds)) {
                $query->orWhereIn('driver_id', $vendorRiderIds);
            }
            if (!empty($pincodes)) {
                $query->orWhereIn('pincode', $pincodes);
            }
        });

        // 2. Summary Metrics Calculations (Overall & Period-based)
        $totalBookingsCount = (clone $baseVendorQuery)->count();
        $completedBookingsCount = (clone $baseVendorQuery)->where('status', 2)->count();
        $inProgressBookingsCount = (clone $baseVendorQuery)->where('status', 1)->count();
        $cancelledBookingsCount = (clone $baseVendorQuery)->where('status', 3)->count();

        // Summary Earnings
        $completedBookingIdsAll = (clone $baseVendorQuery)->where('status', 2)->pluck('booking_id')->toArray();
        $totalEarningsAll = !empty($completedBookingIdsAll) ? (float) DB::table('booking_payment')->whereIn('booking_id', $completedBookingIdsAll)->sum('total') : 0.0;

        // Today's Earnings
        $todayBookingIds = (clone $baseVendorQuery)->where('status', 2)->whereDate('created_at', Carbon::today())->pluck('booking_id')->toArray();
        $todayEarnings = !empty($todayBookingIds) ? (float) DB::table('booking_payment')->whereIn('booking_id', $todayBookingIds)->sum('total') : 0.0;

        // This Week's Earnings
        $weekBookingIds = (clone $baseVendorQuery)->where('status', 2)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->pluck('booking_id')->toArray();
        $weekEarnings = !empty($weekBookingIds) ? (float) DB::table('booking_payment')->whereIn('booking_id', $weekBookingIds)->sum('total') : 0.0;

        // This Month's Earnings
        $monthBookingIds = (clone $baseVendorQuery)->where('status', 2)->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->pluck('booking_id')->toArray();
        $monthEarnings = !empty($monthBookingIds) ? (float) DB::table('booking_payment')->whereIn('booking_id', $monthBookingIds)->sum('total') : 0.0;

        // 3. Apply Period & Specific Filters to Detailed Query
        $filteredQuery = clone $baseVendorQuery;

        $period = $request->get('period', 'this_month');
        $startDate = null;
        $endDate = Carbon::now();

        switch ($period) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'yesterday':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday()->endOfDay();
                break;
            case 'this_week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'this_month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'custom':
                if ($request->filled('start_date')) {
                    $startDate = Carbon::parse($request->start_date)->startOfDay();
                }
                if ($request->filled('end_date')) {
                    $endDate = Carbon::parse($request->end_date)->endOfDay();
                }
                break;
            case 'all':
                $startDate = null;
                break;
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
        }

        if ($startDate) {
            $filteredQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Filter by booking status
        if ($request->has('status') && $request->status !== null && $request->status !== '') {
            $filteredQuery->where('status', (int) $request->status);
        }

        // Filter by service / category
        if ($request->filled('category_id') || $request->filled('service_id')) {
            $catId = $request->input('category_id') ?: $request->input('service_id');
            $filteredQuery->where('category', $catId);
        }

        // Filter by rider / driver
        if ($request->filled('rider_id') || $request->filled('driver_id')) {
            $driverId = $request->input('rider_id') ?: $request->input('driver_id');
            $filteredQuery->where('driver_id', $driverId);
        }

        // Calculate selected period earnings
        $selectedCompletedIds = (clone $filteredQuery)->where('status', 2)->pluck('booking_id')->toArray();
        $selectedPeriodEarnings = !empty($selectedCompletedIds) ? (float) DB::table('booking_payment')->whereIn('booking_id', $selectedCompletedIds)->sum('total') : 0.0;

        // 4. Paginate detailed bookings
        $perPage = (int) $request->get('per_page', 15);
        $paginatedBookings = $filteredQuery->orderBy('created_at', 'desc')->paginate($perPage);

        // Pre-fetch related drivers, users, categories, and payments to avoid N+1 queries
        $bookingIds = collect($paginatedBookings->items())->pluck('booking_id')->filter()->toArray();
        $driverIds = collect($paginatedBookings->items())->pluck('driver_id')->filter()->toArray();
        $customerIds = collect($paginatedBookings->items())->pluck('customer_id')->filter()->toArray();
        $categoryIds = collect($paginatedBookings->items())->pluck('category')->filter()->toArray();

        $driversMap = !empty($driverIds) ? Driver::whereIn('id', $driverIds)->get()->keyBy('id') : collect();
        $customersMap = !empty($customerIds) ? User::whereIn('id', $customerIds)->get()->keyBy('id') : collect();
        $categoriesMap = !empty($categoryIds) ? Category::whereIn('id', $categoryIds)->get()->keyBy('id') : collect();
        $paymentsMap = !empty($bookingIds) ? DB::table('booking_payment')->whereIn('booking_id', $bookingIds)->get()->keyBy('booking_id') : collect();

        $formattedBookings = collect($paginatedBookings->items())->map(function ($b) use ($driversMap, $customersMap, $categoriesMap, $paymentsMap) {
            $driver = $b->driver_id ? $driversMap->get($b->driver_id) : null;
            $customer = $b->customer_id ? $customersMap->get($b->customer_id) : null;
            $category = $b->category ? $categoriesMap->get($b->category) : null;
            $payment = $paymentsMap->get($b->booking_id);

            $statusText = match ((int) $b->status) {
                0 => 'Pending',
                1 => 'In Progress',
                2 => 'Completed',
                3 => 'Cancelled',
                default => 'Unknown',
            };

            return [
                'id' => (int) $b->id,
                'booking_id' => (string) $b->booking_id,
                'status' => (int) $b->status,
                'status_text' => $statusText,
                'category' => [
                    'id' => (int) ($b->category ?? 0),
                    'name' => $category ? $category->category : 'Service #' . $b->category,
                ],
                'rider' => $driver ? [
                    'id' => (int) $driver->id,
                    'name' => (string) $driver->name,
                    'mobile' => (string) $driver->mobile,
                ] : null,
                'customer' => [
                    'name' => $customer ? (string) ($customer->name ?: ($customer->firstname . ' ' . $customer->lastname)) : ($b->external_name ?: 'Customer'),
                    'mobile' => $customer ? (string) $customer->phone : ($b->external_phone ?: ''),
                ],
                'distance' => (string) ($b->distance ?? ''),
                'duration' => (string) ($b->duration ?? ''),
                'pincode' => (string) ($b->pincode ?? ''),
                'payment' => [
                    'base_fare' => (float) ($payment->base_price ?? 0),
                    'tax' => (float) ($payment->tax ?? 0),
                    'service_charge' => (float) ($payment->service_cost ?? 0),
                    'discount' => (float) ($payment->coupon_amount ?? 0),
                    'total_amount' => (float) ($payment->total ?? 0),
                    'vendor_earning' => (float) ($payment->total ?? 0),
                    'payment_type' => (string) ($payment->type ?? 'Cash'),
                    'payment_status' => (string) ($payment->status ?? 'pending'),
                ],
                'created_at' => $b->created_at ? $b->created_at->toDateTimeString() : null,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Earnings and reports retrieved successfully',
            'data' => [
                'summary' => [
                    'total_earnings' => round($totalEarningsAll, 2),
                    'today_earnings' => round($todayEarnings, 2),
                    'week_earnings' => round($weekEarnings, 2),
                    'month_earnings' => round($monthEarnings, 2),
                    'selected_range_earnings' => round($selectedPeriodEarnings, 2),
                    'total_bookings' => $totalBookingsCount,
                    'completed_bookings' => $completedBookingsCount,
                    'in_progress_bookings' => $inProgressBookingsCount,
                    'cancelled_bookings' => $cancelledBookingsCount,
                ],
                'filters_applied' => [
                    'period' => $period,
                    'start_date' => $startDate ? $startDate->toDateTimeString() : null,
                    'end_date' => $endDate ? $endDate->toDateTimeString() : null,
                    'status' => $request->get('status'),
                    'category_id' => $request->get('category_id') ?: $request->get('service_id'),
                    'rider_id' => $request->get('rider_id') ?: $request->get('driver_id'),
                ],
                'reports' => [
                    'current_page' => $paginatedBookings->currentPage(),
                    'per_page' => $paginatedBookings->perPage(),
                    'total' => $paginatedBookings->total(),
                    'last_page' => $paginatedBookings->lastPage(),
                    'bookings' => $formattedBookings,
                ]
            ]
        ]);
    }
}
