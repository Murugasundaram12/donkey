<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Coupon;
use App\Models\Driver;
use App\Models\Pincode;
use App\Models\Pushnotification;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get Vendor Dashboard Statistics
     */
    public function index(Request $request)
    {
        $vendor = $request->user();

        // Retrieve vendor's pincodes
        $subscribersPin = json_decode($vendor->pincode, true);
        $subscribersPin = is_array($subscribersPin) ? array_values($subscribersPin) : [];
        $pincodes = Pincode::whereIn('id', $subscribersPin)->pluck('pincode')->toArray();

        // Base booking query scoped strictly to vendor
        $bookingQuery = Booking::query()->where(function ($query) use ($vendor, $pincodes) {
            $query->where('assigned_subscriber_id', $vendor->id)
                  ->orWhere('provider_accepted_by', $vendor->id);
            if (!empty($pincodes)) {
                $query->orWhereIn('pincode', $pincodes);
            }
        });

        // Booking Counts
        $totalBookings = (clone $bookingQuery)->count();
        $todayBookings = (clone $bookingQuery)->whereDate('created_at', Carbon::today())->count();
        $pendingBookings = (clone $bookingQuery)->where('status', 0)->count();
        $inProgressBookings = (clone $bookingQuery)->where('status', 1)->count();
        $completedBookings = (clone $bookingQuery)->where('status', 2)->count();
        $cancelledBookings = (clone $bookingQuery)->where('status', 3)->count();
        $incompleteBookings = (clone $bookingQuery)->whereIn('status', [0, 1])->count();

        // Earnings Calculation
        $completedBookingIds = (clone $bookingQuery)->where('status', 2)->pluck('booking_id')->toArray();
        $totalEarnings = DB::table('booking_payment')
            ->whereIn('booking_id', $completedBookingIds)
            ->sum('total');

        $todayCompletedBookingIds = (clone $bookingQuery)
            ->where('status', 2)
            ->whereDate('created_at', Carbon::today())
            ->pluck('booking_id')
            ->toArray();
        
        $todayEarnings = DB::table('booking_payment')
            ->whereIn('booking_id', $todayCompletedBookingIds)
            ->sum('total');

        // Rider Statistics
        $driverQuery = Driver::where('subscriberId', $vendor->id);
        $totalRiders = (clone $driverQuery)->count();
        $pendingRiders = (clone $driverQuery)->where('status', 0)->count();
        
        $driverUserIds = \Illuminate\Support\Facades\Schema::hasColumn('driver', 'userid')
            ? (clone $driverQuery)->pluck('userid')->filter()->toArray()
            : (clone $driverQuery)->pluck('id')->filter()->toArray();
        $onlineRiders = 0;
        $offlineRiders = 0;
        if (!empty($driverUserIds)) {
            $onlineRiders = User::whereIn('id', $driverUserIds)->where('is_live', 1)->count();
            $offlineRiders = User::whereIn('id', $driverUserIds)->where('is_live', '!=', 1)->count();
        }

        // Active Coupons
        $activeCouponsCount = 0;
        if (\Illuminate\Support\Facades\Schema::hasTable('coupons')) {
            $couponQuery = Coupon::query();
            if (\Illuminate\Support\Facades\Schema::hasColumn('coupons', 'status')) {
                $couponQuery->where('status', 1);
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('coupons', 'created_by')) {
                $couponQuery->where(function ($q) use ($vendor, $pincodes) {
                    $q->where('created_by', $vendor->id);
                    if (!empty($pincodes) && \Illuminate\Support\Facades\Schema::hasColumn('coupons', 'pincode_id')) {
                        $q->orWhereIn('pincode_id', $pincodes);
                    }
                });
            }
            $activeCouponsCount = $couponQuery->count();
        }

        // Notifications
        $notificationsCount = Pushnotification::count();

        return response()->json([
            'status' => true,
            'message' => 'Dashboard metrics fetched successfully',
            'data' => [
                'bookings_summary' => [
                    'total' => $totalBookings,
                    'today' => $todayBookings,
                    'pending' => $pendingBookings,
                    'in_progress' => $inProgressBookings,
                    'completed' => $completedBookings,
                    'cancelled' => $cancelledBookings,
                    'incomplete' => $incompleteBookings,
                ],
                'earnings_summary' => [
                    'total_earnings' => (float) round($totalEarnings, 2),
                    'today_earnings' => (float) round($todayEarnings, 2),
                ],
                'riders_summary' => [
                    'total_riders' => $totalRiders,
                    'online_riders' => $onlineRiders,
                    'offline_riders' => $offlineRiders,
                    'pending_approvals' => $pendingRiders,
                ],
                'active_coupons_count' => $activeCouponsCount,
                'notifications_count' => $notificationsCount,
            ]
        ]);
    }
}
