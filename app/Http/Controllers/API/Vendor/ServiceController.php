<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Driver;
use App\Models\Pincode;
use App\Models\Pincodebasedcategory;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    private static array $serviceNames = [
        1 => 'Bike Taxi',
        2 => 'Delivery',
        3 => 'Courier',
        4 => 'Auto',
        5 => 'Rentals',
    ];

    /**
     * Get vendor's available services with status & pending rider approvals.
     */
    public function index(Request $request)
    {
        $vendor = $request->user();
        $services = $this->getVendorServicesData($vendor);

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Services retrieved successfully',
            'data' => $services,
        ]);
    }

    /**
     * Update service status for authenticated vendor.
     */
    public function updateStatus(Request $request, $serviceId)
    {
        $validator = Validator::make([
            'service_id' => $serviceId,
            'status' => $request->status,
        ], [
            'service_id' => 'required|integer|exists:category,id',
            'status' => 'required|string|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $vendor = $request->user();
        $serviceId = (int) $serviceId;
        $statusStr = strtolower($request->status);
        $statusInt = ($statusStr === 'active') ? 1 : 0;

        $pincodeIds = $this->getVendorPincodeIds($vendor);

        if (!empty($pincodeIds)) {
            foreach ($pincodeIds as $pinId) {
                Pincodebasedcategory::updateOrCreate(
                    [
                        'subscriber_id' => $vendor->id,
                        'pincode_id' => $pinId,
                        'category_id' => $serviceId,
                    ],
                    [
                        'status' => $statusInt,
                    ]
                );
            }
        } else {
            Pincodebasedcategory::updateOrCreate(
                [
                    'subscriber_id' => $vendor->id,
                    'pincode_id' => 0,
                    'category_id' => $serviceId,
                ],
                [
                    'status' => $statusInt,
                ]
            );
        }

        $category = Category::find($serviceId);
        $serviceName = self::$serviceNames[$serviceId] ?? ($category ? $category->category : "Service #{$serviceId}");

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Service status updated successfully',
            'data' => [
                'id' => $serviceId,
                'name' => $serviceName,
                'status' => $statusStr,
            ],
        ]);
    }

    /**
     * Service dashboard summary.
     */
    public function dashboard(Request $request)
    {
        $vendor = $request->user();
        $services = $this->getVendorServicesData($vendor);
        $pincodes = (new PincodeController())->summary($request)->getData(true)['data'];
        $coupons = $this->getVendorCouponSummary($vendor);

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Service dashboard summary retrieved successfully',
            'data' => [
                'services' => $services,
                'pincode_coverage' => $pincodes,
                'coupons' => $coupons,
            ],
        ]);
    }

    /**
     * Helper to build service statistics for vendor.
     */
    public function getVendorServicesData($vendor): array
    {
        $categories = Category::whereIn('id', array_keys(self::$serviceNames))
            ->orderByRaw('FIELD(id, 1, 2, 4, 3, 5)')
            ->get();
        $pincodeIds = $this->getVendorPincodeIds($vendor);

        $result = [];

        foreach ($categories as $cat) {
            $catId = (int) $cat->id;
            $name = self::$serviceNames[$catId] ?? $cat->category;

            // Determine status
            $pbcQuery = Pincodebasedcategory::where('subscriber_id', $vendor->id)
                ->where('category_id', $catId);

            if (!empty($pincodeIds)) {
                $pbcQuery->whereIn('pincode_id', $pincodeIds);
            }

            $pbcRecords = $pbcQuery->get();

            if ($pbcRecords->isNotEmpty()) {
                $hasActive = $pbcRecords->contains(fn ($item) => (int) $item->status === 1);
                $status = $hasActive ? 'active' : 'inactive';
            } else {
                $status = ((int) $cat->status === 1) ? 'active' : 'inactive';
            }

            // Read-only count of pending rider approvals for this vendor and service
            $pendingRiders = Driver::where('subscriberId', $vendor->id)
                ->where('status', 0)
                ->where(function ($q) use ($catId) {
                    $q->where('type', (string) $catId)
                      ->orWhereRaw("FIND_IN_SET(?, REPLACE(type, ' ', ''))", [$catId]);
                })
                ->count();

            $result[] = [
                'id' => $catId,
                'name' => $name,
                'status' => $status,
                'pending_rider_approvals' => $pendingRiders,
            ];
        }

        return $result;
    }

    private function getVendorCouponSummary($vendor): array
    {
        $pincodeIds = $this->getVendorPincodeIds($vendor);
        $pincodes = Pincode::whereIn('id', $pincodeIds)->pluck('pincode')->toArray();

        $query = Coupon::where(function ($q) use ($vendor, $pincodes) {
            $q->where('created_by', $vendor->id);
            // Legacy unowned coupons remain visible only when their pincode is
            // owned by this vendor; another vendor's coupon is never included.
            if (!empty($pincodes)) {
                $q->orWhere(function ($legacy) use ($pincodes) {
                    $legacy->whereNull('created_by')->whereIn('pincode_id', $pincodes);
                });
            }
        });

        $active = (clone $query)->where('status', 1)->count();
        $inactive = (clone $query)->where('status', '!=', 1)->count();

        return ['active' => $active, 'inactive' => $inactive, 'total' => $active + $inactive];
    }

    private function getVendorPincodeIds($vendor): array
    {
        $subscribersPin = json_decode((string) $vendor->pincode, true);
        $pincodeIds = is_array($subscribersPin) ? array_map('intval', array_values($subscribersPin)) : [];

        $usedByPinIds = Pincode::where('usedBy', $vendor->id)->pluck('id')->toArray();

        return array_values(array_unique(array_merge($pincodeIds, $usedByPinIds)));
    }
}
