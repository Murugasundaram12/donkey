<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\Pincode;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Get Vendor Analytics & Reports
     */
    public function index(Request $request)
    {
        $vendor = $request->user();

        $period = $request->get('period', 'this_month');
        $startDate = null;
        $endDate = Carbon::now();

        switch ($period) {
            case 'today':
                $startDate = Carbon::today();
                break;
            case 'yesterday':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday()->endOfDay();
                break;
            case 'this_week':
                $startDate = Carbon::now()->startOfWeek();
                break;
            case 'this_month':
                $startDate = Carbon::now()->startOfMonth();
                break;
            case 'custom':
                if ($request->filled('start_date')) {
                    $startDate = Carbon::parse($request->start_date);
                }
                if ($request->filled('end_date')) {
                    $endDate = Carbon::parse($request->end_date)->endOfDay();
                }
                break;
            default:
                $startDate = Carbon::now()->startOfMonth();
                break;
        }

        $subscribersPin = json_decode($vendor->pincode, true);
        $subscribersPin = is_array($subscribersPin) ? array_values($subscribersPin) : [];
        $pincodes = Pincode::whereIn('id', $subscribersPin)->pluck('pincode')->toArray();

        $bookingQuery = Booking::query()
            ->where(function ($query) use ($vendor, $pincodes) {
                $query->where('assigned_subscriber_id', $vendor->id)
                      ->orWhere('provider_accepted_by', $vendor->id);
                if (!empty($pincodes)) {
                    $query->orWhereIn('pincode', $pincodes);
                }
            });

        if ($startDate) {
            $bookingQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalBookings = (clone $bookingQuery)->count();
        $completedBookings = (clone $bookingQuery)->where('status', 2)->count();
        $inProgressBookings = (clone $bookingQuery)->where('status', 1)->count();
        $cancelledBookings = (clone $bookingQuery)->where('status', 3)->count();

        // Earnings
        $completedBookingIds = (clone $bookingQuery)->where('status', 2)->pluck('booking_id')->toArray();
        $totalEarnings = DB::table('booking_payment')
            ->whereIn('booking_id', $completedBookingIds)
            ->sum('total');

        // Category breakdown (1: Bike Taxi, 2: Pickup, 3: Buy & Deliver, 4: Auto, 5: Cab)
        $categories = [
            1 => 'Bike Taxi',
            2 => 'Pickup',
            3 => 'Drop & Delivery',
            4 => 'Auto',
            5 => 'Cab'
        ];

        $categoryBreakdown = [];
        foreach ($categories as $catId => $catName) {
            $count = (clone $bookingQuery)->where('category', $catId)->count();
            $catBookingIds = (clone $bookingQuery)->where('category', $catId)->where('status', 2)->pluck('booking_id')->toArray();
            $catRevenue = DB::table('booking_payment')->whereIn('booking_id', $catBookingIds)->sum('total');

            $categoryBreakdown[] = [
                'category_id' => $catId,
                'category_name' => $catName,
                'total_bookings' => $count,
                'revenue' => (float) round($catRevenue, 2),
            ];
        }

        // Rider activity
        $totalRiders = Driver::where('subscriberId', $vendor->id)->count();

        return response()->json([
            'status' => true,
            'message' => 'Report generated successfully',
            'data' => [
                'period' => $period,
                'start_date' => $startDate ? $startDate->toDateTimeString() : null,
                'end_date' => $endDate->toDateTimeString(),
                'summary' => [
                    'total_bookings' => $totalBookings,
                    'completed_bookings' => $completedBookings,
                    'in_progress_bookings' => $inProgressBookings,
                    'cancelled_bookings' => $cancelledBookings,
                    'total_earnings' => (float) round($totalEarnings, 2),
                    'total_riders' => $totalRiders,
                ],
                'category_breakdown' => $categoryBreakdown,
            ]
        ]);
    }
}
