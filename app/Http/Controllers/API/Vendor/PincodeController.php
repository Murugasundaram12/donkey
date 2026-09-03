<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Pincode;
use App\Models\Pincodebasedcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PincodeController extends Controller
{
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
