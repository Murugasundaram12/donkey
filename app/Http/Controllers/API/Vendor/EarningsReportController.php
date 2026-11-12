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
use Illuminate\Validation\Rule;

class EarningsReportController extends Controller
{
    /**
     * Get Consolidated Vendor Earnings & Reports
     */
    public function index(Request $request)
    {
        $request->validate([
            'period' => ['nullable', Rule::in(['today', 'yesterday', 'this_week', 'this_month', 'custom', 'all'])],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'category_id' => ['nullable', 'integer', 'exists:category,id'],
            'service_id' => ['nullable', 'integer', 'exists:category,id'],
        ]);
        $vendor = $request->user();

        // Financial reports are strictly vendor-owned. Pincode availability is
        // intentionally not an ownership boundary for earnings.
        $vendorRiders = Driver::where('subscriberId', $vendor->id)->get();
        $vendorRiderIds = $vendorRiders->flatMap(fn ($rider) => [$rider->id, $rider->userid])
            ->filter()->unique()->values()->all();

        // Base booking query strictly scoped to authenticated vendor
        $baseVendorQuery = Booking::query()->where(function ($query) use ($vendor, $vendorRiderIds) {
            $query->where('assigned_subscriber_id', $vendor->id)
                  ->orWhere('provider_accepted_by', $vendor->id);
            if (!empty($vendorRiderIds)) {
                $query->orWhereIn('driver_id', $vendorRiderIds);
                $query->orWhereIn('accepted', $vendorRiderIds);
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

        $period = $request->get('period');
        if (empty($period)) {
            $period = ($request->filled('start_date') || $request->filled('end_date')) ? 'custom' : 'this_month';
        }
        $startDate = null;
        $endDate = Carbon::now()->endOfDay();
        $prevStartDate = null;
        $prevEndDate = null;
        $comparisonAvailable = false;

        switch ($period) {
            case 'today':
                $startDate = Carbon::today()->startOfDay();
                $endDate = Carbon::today()->endOfDay();
                $prevStartDate = Carbon::yesterday()->startOfDay();
                $prevEndDate = Carbon::yesterday()->endOfDay();
                $comparisonAvailable = true;
                break;
            case 'yesterday':
                $startDate = Carbon::yesterday()->startOfDay();
                $endDate = Carbon::yesterday()->endOfDay();
                $prevStartDate = Carbon::yesterday()->subDay()->startOfDay();
                $prevEndDate = Carbon::yesterday()->subDay()->endOfDay();
                $comparisonAvailable = true;
                break;
            case 'this_week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $prevStartDate = Carbon::now()->subWeek()->startOfWeek();
                $prevEndDate = Carbon::now()->subWeek()->endOfWeek();
                $comparisonAvailable = true;
                break;
            case 'this_month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $prevStartDate = Carbon::now()->subMonth()->startOfMonth();
                $prevEndDate = Carbon::now()->subMonth()->endOfMonth();
                $comparisonAvailable = true;
                break;
            case 'custom':
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $startDate = Carbon::parse($request->start_date)->startOfDay();
                    $endDate = Carbon::parse($request->end_date)->endOfDay();
                    $diffDays = $startDate->diffInDays($endDate) + 1;
                    $prevEndDate = $startDate->copy()->subSecond();
                    $prevStartDate = $prevEndDate->copy()->subDays($diffDays - 1)->startOfDay();
                    $comparisonAvailable = true;
                } elseif ($request->filled('start_date')) {
                    $startDate = Carbon::parse($request->start_date)->startOfDay();
                    $endDate = Carbon::now()->endOfDay();
                    $diffDays = $startDate->diffInDays($endDate) + 1;
                    $prevEndDate = $startDate->copy()->subSecond();
                    $prevStartDate = $prevEndDate->copy()->subDays($diffDays - 1)->startOfDay();
                    $comparisonAvailable = true;
                } elseif ($request->filled('end_date')) {
                    $endDate = Carbon::parse($request->end_date)->endOfDay();
                    $startDate = Carbon::now()->startOfMonth();
                    $diffDays = $startDate->diffInDays($endDate) + 1;
                    $prevEndDate = $startDate->copy()->subSecond();
                    $prevStartDate = $prevEndDate->copy()->subDays($diffDays - 1)->startOfDay();
                    $comparisonAvailable = true;
                } else {
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                    $prevStartDate = Carbon::now()->subMonth()->startOfMonth();
                    $prevEndDate = Carbon::now()->subMonth()->endOfMonth();
                    $comparisonAvailable = true;
                }
                break;
            case 'all':
                $startDate = null;
                $endDate = null;
                $comparisonAvailable = false;
                break;
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $prevStartDate = Carbon::now()->subMonth()->startOfMonth();
                $prevEndDate = Carbon::now()->subMonth()->endOfMonth();
                $comparisonAvailable = true;
                break;
        }

        if ($startDate && $endDate) {
            $filteredQuery->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $filteredQuery->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $filteredQuery->where('created_at', '<=', $endDate);
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

        $selectedTotalBookings = (clone $filteredQuery)->count();
        $selectedCompletedBookings = (clone $filteredQuery)->where('status', 2)->get(['booking_id', 'category']);
        $selectedCompletedCount = $selectedCompletedBookings->count();
        $selectedIncompleteCount = (clone $filteredQuery)->whereIn('status', [0, 1])->count();
        $selectedCancelledCount = (clone $filteredQuery)->where('status', 3)->count();

        // 3a. Previous Period Comparison Metrics (Dynamic Percentages)
        $prevTotalBookings = 0;
        $prevCompletedBookings = 0;
        $prevCancelledBookings = 0;
        $prevTotalEarnings = 0.0;

        if ($comparisonAvailable && $prevStartDate && $prevEndDate) {
            $prevQuery = clone $baseVendorQuery;
            $prevQuery->whereBetween('created_at', [$prevStartDate, $prevEndDate]);

            if ($request->has('status') && $request->status !== null && $request->status !== '') {
                $prevQuery->where('status', (int) $request->status);
            }

            if ($request->filled('category_id') || $request->filled('service_id')) {
                $catId = $request->input('category_id') ?: $request->input('service_id');
                $prevQuery->where('category', $catId);
            }

            if ($request->filled('rider_id') || $request->filled('driver_id')) {
                $driverId = $request->input('rider_id') ?: $request->input('driver_id');
                $prevQuery->where('driver_id', $driverId);
            }

            $prevTotalBookings = (clone $prevQuery)->count();
            $prevCompletedIds = (clone $prevQuery)->where('status', 2)->pluck('booking_id')->toArray();
            $prevCompletedBookings = count($prevCompletedIds);
            $prevCancelledBookings = (clone $prevQuery)->where('status', 3)->count();
            $prevTotalEarnings = !empty($prevCompletedIds)
                ? (float) DB::table('booking_payment')->whereIn('booking_id', $prevCompletedIds)->sum('total')
                : 0.0;
        }

        $bookingsComp = $this->calculateMetricComparison($selectedTotalBookings, $prevTotalBookings, $comparisonAvailable);
        $earningsComp = $this->calculateMetricComparison(round($selectedPeriodEarnings, 2), round($prevTotalEarnings, 2), $comparisonAvailable);
        $completedComp = $this->calculateMetricComparison($selectedCompletedCount, $prevCompletedBookings, $comparisonAvailable);
        $cancelledComp = $this->calculateMetricComparison($selectedCancelledCount, $prevCancelledBookings, $comparisonAvailable);

        $selectedPayments = !empty($selectedCompletedIds)
            ? DB::table('booking_payment')->whereIn('booking_id', $selectedCompletedIds)->get()->keyBy('booking_id')
            : collect();

        $serviceTotals = collect([1, 2, 3, 4, 5])->mapWithKeys(fn ($id) => [(string) $id => 0.0]);
        $selectedCompletedBookings->groupBy('category')->each(function ($bookings, $categoryId) use (&$serviceTotals, $selectedPayments) {
            $serviceTotals[(string) $categoryId] = (float) $bookings->sum(
                fn ($booking) => (float) ($selectedPayments->get($booking->booking_id)->total ?? 0)
            );
        });
        $categoryNames = Category::whereIn('id', [1, 2, 3, 4, 5])->pluck('category', 'id');
        $displayNames = [1 => 'Bike Taxi', 2 => 'Delivery', 3 => 'Courier', 4 => 'Auto', 5 => 'Rentals'];
        $topServices = $serviceTotals->sortDesc()->map(function ($amount, $categoryId) use ($categoryNames, $displayNames, $selectedPeriodEarnings) {
            $categoryId = (int) $categoryId;
            return [
                'id' => $categoryId,
                'name' => (string) ($displayNames[$categoryId] ?? $categoryNames->get($categoryId) ?: 'Service #' . $categoryId),
                'earnings' => round($amount, 2),
                'percentage' => $selectedPeriodEarnings > 0 ? round(($amount / $selectedPeriodEarnings) * 100, 2) : 0.0,
            ];
        })->values();

        // Build the chart from completed bookings and their persisted payments.
        $chartData = (clone $filteredQuery)->where('status', 2)->get(['booking_id', 'created_at'])
            ->groupBy(fn ($booking) => Carbon::parse($booking->created_at)->format('Y-m-d'))
            ->map(function ($bookings, $date) use ($selectedPayments) {
                $amount = $bookings->sum(fn ($booking) => (float) ($selectedPayments->get($booking->booking_id)->total ?? 0));
                return ['date' => $date, 'amount' => round($amount, 2)];
            })->sortKeys()->values();

        // No booking commission rule exists in this codebase by default. platform_fee is
        // subscription accounting, not a per-booking commission.
        $commissionRule = config('services.vendor.commission_rule')
            ?? config('services.commission.rule')
            ?? config('donkey.commission_rule')
            ?? config('services.vendor.commission')
            ?? config('donkey.commission')
            ?? ($vendor->commission_rule ?? null)
            ?? ($vendor->commission ?? null);

        $hasCommissionRule = !empty($commissionRule);
        $commissionAvailable = $hasCommissionRule;
        $commissionNote = null;

        if ($hasCommissionRule) {
            $noteCandidate = config('services.vendor.commission_note')
                ?? config('services.commission.note')
                ?? config('donkey.commission_note')
                ?? ($vendor->commission_note ?? null);

            if (is_string($noteCandidate) && trim($noteCandidate) !== '') {
                $commissionNote = trim($noteCandidate);
            } elseif (is_string($commissionRule) && trim($commissionRule) !== '' && !is_numeric($commissionRule)) {
                $commissionNote = trim($commissionRule);
            }
        }

        $commission = null;
        $netEarnings = null;
        $avgOrderValue = $selectedCompletedCount > 0 ? $selectedPeriodEarnings / $selectedCompletedCount : 0.0;

        // 4. Paginate detailed bookings
        $perPage = (int) $request->get('per_page', 15);
        $paginatedBookings = $filteredQuery->orderBy('created_at', 'desc')->paginate($perPage);

        // Pre-fetch related drivers, users, categories, and payments to avoid N+1 queries
        $bookingIds = collect($paginatedBookings->items())->pluck('booking_id')->filter()->toArray();
        $driverIds = collect($paginatedBookings->items())->pluck('driver_id')->filter()->toArray();
        $customerIds = collect($paginatedBookings->items())->pluck('customer_id')->filter()->toArray();
        $categoryIds = collect($paginatedBookings->items())->pluck('category')->filter()->toArray();

        $driversMap = $vendorRiders->flatMap(function ($driver) {
            return [
                (string) $driver->id => $driver,
                (string) $driver->userid => $driver,
            ];
        });
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
                    'total_earnings' => round($selectedPeriodEarnings, 2),
                    'overall_total_earnings' => round($totalEarningsAll, 2),
                    'today_earnings' => round($todayEarnings, 2),
                    'week_earnings' => round($weekEarnings, 2),
                    'month_earnings' => round($monthEarnings, 2),
                    'selected_range_earnings' => round($selectedPeriodEarnings, 2),
                    'total_bookings' => $selectedTotalBookings,
                    'overall_total_bookings' => $totalBookingsCount,
                    'completed_bookings' => $selectedCompletedCount,
                    'overall_completed_bookings' => $completedBookingsCount,
                    'in_progress_bookings' => $selectedIncompleteCount,
                    'cancelled_bookings' => $selectedCancelledCount,
                    'overall_cancelled_bookings' => $cancelledBookingsCount,
                    'selected_total_bookings' => $selectedTotalBookings,
                    'selected_completed_bookings' => $selectedCompletedCount,
                    'selected_incomplete_bookings' => $selectedIncompleteCount,
                    'selected_cancelled_bookings' => $selectedCancelledCount,
                    'avg_order_value' => round($avgOrderValue, 2),
                    'total_bookings_percentage' => $bookingsComp['percentage'],
                    'total_bookings_trend' => $bookingsComp['trend'],
                    'total_bookings_direction' => $bookingsComp['direction'],
                    'total_earnings_percentage' => $earningsComp['percentage'],
                    'total_earnings_trend' => $earningsComp['trend'],
                    'total_earnings_direction' => $earningsComp['direction'],
                    'completed_bookings_percentage' => $completedComp['percentage'],
                    'completed_bookings_trend' => $completedComp['trend'],
                    'completed_bookings_direction' => $completedComp['direction'],
                    'cancelled_bookings_percentage' => $cancelledComp['percentage'],
                    'cancelled_bookings_trend' => $cancelledComp['trend'],
                    'cancelled_bookings_direction' => $cancelledComp['direction'],
                    'comparison_available' => $comparisonAvailable,
                    'comparison' => [
                        'available' => $comparisonAvailable,
                        'previous_period' => [
                            'start_date' => ($comparisonAvailable && $prevStartDate) ? $prevStartDate->toDateTimeString() : null,
                            'end_date' => ($comparisonAvailable && $prevEndDate) ? $prevEndDate->toDateTimeString() : null,
                            'total_bookings' => $prevTotalBookings,
                            'total_earnings' => round($prevTotalEarnings, 2),
                            'completed_bookings' => $prevCompletedBookings,
                            'cancelled_bookings' => $prevCancelledBookings,
                        ],
                        'percentage_changes' => [
                            'total_bookings' => $bookingsComp,
                            'total_earnings' => $earningsComp,
                            'completed' => $completedComp,
                            'cancelled' => $cancelledComp,
                        ],
                    ],
                    'commission' => $commission,
                    'net_earnings' => $netEarnings,
                    'commission_available' => $commissionAvailable,
                    'commission_note' => ($commissionNote !== null && $commissionNote !== '') ? $commissionNote : '0',
                ],
                'comparison' => [
                    'available' => $comparisonAvailable,
                    'previous_period' => [
                        'start_date' => ($comparisonAvailable && $prevStartDate) ? $prevStartDate->toDateTimeString() : null,
                        'end_date' => ($comparisonAvailable && $prevEndDate) ? $prevEndDate->toDateTimeString() : null,
                        'total_bookings' => $prevTotalBookings,
                        'total_earnings' => round($prevTotalEarnings, 2),
                        'completed_bookings' => $prevCompletedBookings,
                        'cancelled_bookings' => $prevCancelledBookings,
                    ],
                    'metrics' => [
                        'total_bookings' => $bookingsComp,
                        'total_earnings' => $earningsComp,
                        'completed_bookings' => $completedComp,
                        'cancelled_bookings' => $cancelledComp,
                    ],
                ],
                'earnings_chart' => $chartData,
                'top_services' => $topServices,
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

    /**
     * Calculate dynamic percentage change and trend direction safely.
     *
     * @param float|int $current
     * @param float|int|null $previous
     * @param bool $comparisonAvailable
     * @return array
     */
    protected function calculateMetricComparison(float|int $current, float|int|null $previous, bool $comparisonAvailable): array
    {
        if (!$comparisonAvailable || $previous === null) {
            return [
                'current' => $current,
                'previous' => null,
                'percentage' => null,
                'trend' => null,
                'direction' => null,
            ];
        }

        if ($previous > 0) {
            $change = round((($current - $previous) / $previous) * 100, 2);
            $trend = $change > 0 ? 'up' : ($change < 0 ? 'down' : 'neutral');
        } elseif ($current > 0) {
            // Previous was 0, current has activity -> 100% growth
            $change = 100.0;
            $trend = 'up';
        } else {
            // Both 0 -> no change
            $change = 0.0;
            $trend = 'neutral';
        }

        return [
            'current' => $current,
            'previous' => $previous,
            'percentage' => $change,
            'trend' => $trend,
            'direction' => $trend,
        ];
    }
}
