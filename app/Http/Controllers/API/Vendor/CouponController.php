<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Pincode;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CouponController extends Controller
{
    /**
     * Active Coupons for Vendor
     */
    public function active(Request $request)
    {
        $vendor = $request->user();

        $subscribersPin = json_decode($vendor->pincode, true);
        $subscribersPin = is_array($subscribersPin) ? array_values($subscribersPin) : [];
        $pincodes = Pincode::whereIn('id', $subscribersPin)->pluck('pincode')->toArray();

        $coupons = Coupon::where('status', 1)
            ->where(function ($q) use ($vendor, $pincodes) {
                $q->where('created_by', $vendor->id);
                if (!empty($pincodes)) {
                    $q->orWhereIn('pincode_id', $pincodes);
                }
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($c) {
                return [
                    'id' => (int) $c->id,
                    'title' => (string) $c->title,
                    'code' => (string) $c->code,
                    'amount' => (float) ($c->amount ?? 0),
                    'percentage' => (float) ($c->percentage ?? 0),
                    'discount_type' => (int) $c->discount_type,
                    'limit' => (int) $c->limit,
                    'start_date' => $c->start_date ? Carbon::parse($c->start_date)->format('Y-m-d') : null,
                    'expiry_date' => $c->expiry_date ? Carbon::parse($c->expiry_date)->format('Y-m-d') : null,
                    'status' => (int) $c->status,
                ];
            });

        return response()->json([
            'status' => true,
            'message' => 'Active coupons retrieved successfully',
            'data' => [
                'items' => $coupons
            ]
        ]);
    }

    /**
     * Coupon Statistics Summary for Vendor
     */
    public function summary(Request $request)
    {
        $vendor = $request->user();

        $subscribersPin = json_decode($vendor->pincode, true);
        $subscribersPin = is_array($subscribersPin) ? array_values($subscribersPin) : [];
        $pincodes = Pincode::whereIn('id', $subscribersPin)->pluck('pincode')->toArray();

        $query = Coupon::where(function ($q) use ($vendor, $pincodes) {
            $q->where('created_by', $vendor->id);
            if (!empty($pincodes)) {
                $q->orWhereIn('pincode_id', $pincodes);
            }
        });

        $activeCount = (clone $query)->where('status', 1)->count();
        $inactiveCount = (clone $query)->where('status', '!=', 1)->count();
        $totalCount = $activeCount + $inactiveCount;

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Coupon summary retrieved successfully',
            'data' => [
                'active' => $activeCount,
                'inactive' => $inactiveCount,
                'total' => $totalCount,
            ]
        ]);
    }
}
