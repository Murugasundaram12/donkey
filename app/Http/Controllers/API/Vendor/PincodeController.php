<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Pincode;
use App\Models\Pincodebasedcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PincodeController extends Controller
{
    private static array $serviceNames = [
        1 => 'Bike Taxi',
        2 => 'Delivery',
        3 => 'Courier',
        4 => 'Auto',
        5 => 'Rentals',
    ];
    /**
     * Get pincode statistics for vendor (Total, Active, Pending, Inactive).
     */
    public function summary(Request $request)
    {
        $vendor = $request->user();
        $pincodeModels = $this->getVendorPincodes($vendor);

        $counts = $this->calculatePincodeCounts($vendor->id, $pincodeModels);

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Pincode summary retrieved successfully',
            'data' => $counts,
        ]);
    }

    /**
     * List vendor pincodes with status.
     */
    public function index(Request $request)
    {
        $vendor = $request->user();
        $pincodeModels = $this->getVendorPincodes($vendor);

        $items = [];
        foreach ($pincodeModels as $pin) {
            $status = $this->getPincodeStatus($vendor->id, $pin->id);
            $items[] = [
                'id' => (int) $pin->id,
                'pincode' => (string) $pin->pincode,
                'state' => (string) ($pin->state ?? ''),
                'district' => (string) ($pin->district ?? ''),
                'city' => (string) ($pin->city ?? ''),
                'taluk' => (string) ($pin->taluk ?? ''),
                'status' => $status,
            ];
        }

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Pincodes retrieved successfully',
            'data' => [
                'total' => count($items),
                'items' => $items,
            ]
        ]);
    }

    /**
     * Update status for a specific pincode belonging to vendor.
     */
    public function updateStatus(Request $request, $pincodeId)
    {
        $validator = Validator::make([
            'pincode_id' => $pincodeId,
            'status' => $request->status,
        ], [
            'pincode_id' => 'required|integer|exists:pincode,id',
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
        $pincodeId = (int) $pincodeId;

        // Verify vendor ownership
        $vendorPincodes = $this->getVendorPincodes($vendor);
        $pin = $vendorPincodes->firstWhere('id', $pincodeId);

        if (!$pin) {
            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Pincode not found or access denied for this vendor.',
            ], 404);
        }

        $statusStr = strtolower($request->status);
        $statusInt = ($statusStr === 'active') ? 1 : 0;

        // Update categories status for this vendor & pincode
        $existingPbcs = Pincodebasedcategory::where('subscriber_id', $vendor->id)
            ->where('pincode_id', $pincodeId)
            ->get();

        if ($existingPbcs->isNotEmpty()) {
            Pincodebasedcategory::where('subscriber_id', $vendor->id)
                ->where('pincode_id', $pincodeId)
                ->update(['status' => $statusInt]);
        } else {
            // Create rows for standard category IDs 1..5
            for ($catId = 1; $catId <= 5; $catId++) {
                Pincodebasedcategory::create([
                    'subscriber_id' => $vendor->id,
                    'pincode_id' => $pincodeId,
                    'category_id' => $catId,
                    'status' => $statusInt,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Pincode status updated successfully',
            'data' => [
                'id' => $pincodeId,
                'pincode' => (string) $pin->pincode,
                'status' => $statusStr,
            ],
        ]);
    }

    /**
     * Get services under a specific pincode for authenticated vendor.
     */
    public function getPincodeServices(Request $request, $pincodeId)
    {
        $vendor = $request->user();
        $pincodeId = (int) $pincodeId;

        $pin = $this->findVendorPincode($vendor, $pincodeId);
        if (!$pin) {
            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Pincode not found or access denied for this vendor.',
            ], 404);
        }

        $existingPbcs = Pincodebasedcategory::where('subscriber_id', $vendor->id)
            ->where('pincode_id', $pincodeId)
            ->get()
            ->keyBy('category_id');

        $categories = Category::whereIn('id', array_keys(self::$serviceNames))
            ->orderByRaw('FIELD(id, 1, 2, 4, 3, 5)')
            ->get();

        $services = [];
        foreach ($categories as $cat) {
            $catId = (int) $cat->id;
            $pbc = $existingPbcs->get($catId);

            // Default status follows web portal default: 1 (active)
            $statusInt = $pbc ? (int) $pbc->status : 1;
            $statusStr = ($statusInt === 1) ? 'active' : 'inactive';

            $services[] = [
                'id' => $catId,
                'category_id' => $catId,
                'name' => self::$serviceNames[$catId] ?? $cat->category,
                'status' => $statusStr,
                'is_active' => ($statusInt === 1),
            ];
        }

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Pincode services retrieved successfully',
            'data' => [
                'pincode_id' => (int) $pin->id,
                'pincode' => (string) $pin->pincode,
                'services' => $services,
            ],
        ]);
    }

    /**
     * Update a single service status for a specific pincode.
     */
    public function updatePincodeServiceStatus(Request $request, $pincodeId, $serviceId)
    {
        $validator = Validator::make([
            'pincode_id' => $pincodeId,
            'service_id' => $serviceId,
            'status' => $request->status,
        ], [
            'pincode_id' => 'required|integer|exists:pincode,id',
            'service_id' => 'required|integer|exists:category,id',
            'status' => 'required',
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
        $pincodeId = (int) $pincodeId;
        $serviceId = (int) $serviceId;

        $pin = $this->findVendorPincode($vendor, $pincodeId);
        if (!$pin) {
            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Pincode not found or access denied for this vendor.',
            ], 404);
        }

        $statusInt = $this->normalizeStatus($request->status);
        if ($statusInt === null) {
            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Validation error',
                'errors' => ['status' => ['The status must be active, inactive, 1, or 0.']],
            ], 422);
        }

        Pincodebasedcategory::updateOrCreate(
            [
                'subscriber_id' => $vendor->id,
                'pincode_id' => $pincodeId,
                'category_id' => $serviceId,
            ],
            [
                'status' => $statusInt,
            ]
        );

        $category = Category::find($serviceId);
        $serviceName = self::$serviceNames[$serviceId] ?? ($category ? $category->category : "Service #{$serviceId}");
        $statusStr = ($statusInt === 1) ? 'active' : 'inactive';

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Service status for pincode updated successfully',
            'data' => [
                'pincode_id' => (int) $pin->id,
                'pincode' => (string) $pin->pincode,
                'service_id' => $serviceId,
                'service_name' => $serviceName,
                'status' => $statusStr,
                'is_active' => ($statusInt === 1),
            ],
        ]);
    }

    /**
     * Batch update service status across multiple pincodes.
     */
    public function batchUpdatePincodeServices(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|integer|exists:category,id',
            'pincode_ids' => 'required|array|min:1',
            'pincode_ids.*' => 'required|integer|exists:pincode,id',
            'status' => 'required',
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
        $serviceId = (int) $request->service_id;
        $statusInt = $this->normalizeStatus($request->status);

        if ($statusInt === null) {
            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Validation error',
                'errors' => ['status' => ['The status must be active, inactive, 1, or 0.']],
            ], 422);
        }

        $requestedPincodeIds = array_values(array_unique(array_map('intval', $request->pincode_ids)));

        // Verify that EVERY requested pincode belongs to the vendor
        $vendorPincodes = $this->getVendorPincodes($vendor);
        $vendorPincodeIds = $vendorPincodes->pluck('id')->all();

        // Also check if assigned via Pincodebasedcategory
        $extraAssignedIds = Pincodebasedcategory::where('subscriber_id', $vendor->id)
            ->whereIn('pincode_id', $requestedPincodeIds)
            ->pluck('pincode_id')
            ->all();

        $allAuthorizedIds = array_unique(array_merge($vendorPincodeIds, $extraAssignedIds));
        $unauthorized = array_values(array_diff($requestedPincodeIds, $allAuthorizedIds));

        if (!empty($unauthorized)) {
            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'One or more pincodes not found or access denied for this vendor.',
                'errors' => [
                    'pincode_ids' => ['Unauthorized or unassigned pincode IDs: ' . implode(', ', $unauthorized)],
                ],
            ], 403);
        }

        DB::transaction(function () use ($vendor, $requestedPincodeIds, $serviceId, $statusInt) {
            foreach ($requestedPincodeIds as $pinId) {
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
        });

        $category = Category::find($serviceId);
        $serviceName = self::$serviceNames[$serviceId] ?? ($category ? $category->category : "Service #{$serviceId}");
        $statusStr = ($statusInt === 1) ? 'active' : 'inactive';

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Service status updated for selected pincodes successfully',
            'data' => [
                'service_id' => $serviceId,
                'service_name' => $serviceName,
                'status' => $statusStr,
                'is_active' => ($statusInt === 1),
                'updated_pincode_ids' => $requestedPincodeIds,
                'total_updated' => count($requestedPincodeIds),
            ],
        ]);
    }

    /**
     * Find an authorized pincode model for vendor.
     */
    private function findVendorPincode($vendor, int $pincodeId): ?Pincode
    {
        $vendorPincodes = $this->getVendorPincodes($vendor);
        $pin = $vendorPincodes->firstWhere('id', $pincodeId);

        if (!$pin) {
            $hasMapping = Pincodebasedcategory::where('subscriber_id', $vendor->id)
                ->where('pincode_id', $pincodeId)
                ->exists();
            if ($hasMapping) {
                return Pincode::find($pincodeId);
            }
        }

        return $pin;
    }

    /**
     * Normalize status input to integer 0 or 1.
     */
    private function normalizeStatus($status): ?int
    {
        if (is_null($status)) {
            return null;
        }

        if (is_bool($status)) {
            return $status ? 1 : 0;
        }

        if (is_numeric($status)) {
            $intVal = (int) $status;
            return in_array($intVal, [0, 1], true) ? $intVal : null;
        }

        if (is_string($status)) {
            $lower = strtolower(trim($status));
            if (in_array($lower, ['active', 'enable', 'enabled', '1', 'true'], true)) {
                return 1;
            }
            if (in_array($lower, ['inactive', 'disable', 'disabled', '0', 'false'], true)) {
                return 0;
            }
        }

        return null;
    }

    private function getVendorPincodes($vendor)
    {
        $subscribersPin = json_decode((string) $vendor->pincode, true);
        $pincodeIds = is_array($subscribersPin) ? array_map('intval', array_values($subscribersPin)) : [];

        $usedByPinIds = Pincode::where('usedBy', $vendor->id)->pluck('id')->toArray();
        $allPinIds = array_values(array_unique(array_merge($pincodeIds, $usedByPinIds)));

        if (empty($allPinIds)) {
            return collect();
        }

        return Pincode::whereIn('id', $allPinIds)->get();
    }

    private function getPincodeStatus(int $vendorId, int $pincodeId): string
    {
        $pbcs = Pincodebasedcategory::where('subscriber_id', $vendorId)
            ->where('pincode_id', $pincodeId)
            ->get();

        if ($pbcs->isEmpty()) {
            return 'pending';
        }

        $hasActive = $pbcs->contains(fn ($item) => (int) $item->status === 1);
        return $hasActive ? 'active' : 'inactive';
    }

    private function calculatePincodeCounts(int $vendorId, $pincodeModels): array
    {
        $total = $pincodeModels->count();
        $active = 0;
        $pending = 0;
        $inactive = 0;

        foreach ($pincodeModels as $pin) {
            $status = $this->getPincodeStatus($vendorId, $pin->id);
            if ($status === 'active') {
                $active++;
            } elseif ($status === 'inactive') {
                $inactive++;
            } else {
                $pending++;
            }
        }

        return [
            'total' => $total,
            'active' => $active,
            'pending' => $pending,
            'inactive' => $inactive,
        ];
    }
}
