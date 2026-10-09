<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $coupons = $this->visibleCoupons($request)
            ->latest()->paginate((int) $request->get('per_page', 15));

        return response()->json(['status' => true, 'message' => 'Coupons retrieved successfully', 'data' => [
            'current_page' => $coupons->currentPage(), 'per_page' => $coupons->perPage(),
            'total' => $coupons->total(), 'last_page' => $coupons->lastPage(),
            'items' => collect($coupons->items())->map(fn ($coupon) => $this->serialize($coupon)),
        ]]);
    }

    public function show(Request $request, $id)
    {
        $coupon = $this->visibleCoupons($request)->whereKey($id)->first();
        if (!$coupon) return $this->notFound();
        return response()->json(['status' => true, 'message' => 'Coupon retrieved successfully', 'data' => ['coupon' => $this->serialize($coupon)]]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules($request));
        if ($validator->fails()) return $this->validationError($validator);

        $data = $validator->validated();
        if ($error = $this->pincodeOwnershipError($request, $data)) return $error;
        $data['created_by'] = $request->user()->id;
        $data['role'] = 'SUBSCRIBER';
        $coupon = Coupon::create($data);
        return response()->json(['status' => true, 'message' => 'Coupon created successfully', 'data' => ['coupon' => $this->serialize($coupon)]], 201);
    }

    public function update(Request $request, $id)
    {
        $coupon = $this->editableCoupon($request, $id);
        if (!$coupon) return $this->notFound();
        $validator = Validator::make($request->all(), $this->rules($request, $coupon));
        if ($validator->fails()) return $this->validationError($validator);
        $data = $validator->validated();
        if ($error = $this->pincodeOwnershipError($request, $data)) return $error;

        // A vendor may edit an admin coupon only within the pincode scope
        // that made the coupon visible to that vendor.
        if ($coupon->role !== 'SUBSCRIBER') {
            if (array_key_exists('type', $data) && (int) $data['type'] !== 2) {
                return response()->json(['status' => false, 'message' => 'Validation error', 'errors' => [
                    'type' => ['Admin pincode coupon type cannot be changed.'],
                ]], 422);
            }
            if (array_key_exists('pincode_id', $data)
                && (int) $data['pincode_id'] !== (int) $coupon->pincode_id) {
                return response()->json(['status' => false, 'message' => 'Validation error', 'errors' => [
                    'pincode_id' => ['Coupon pincode cannot be changed.'],
                ]], 422);
            }
        }
        $coupon->update($data);
        return response()->json(['status' => true, 'message' => 'Coupon updated successfully', 'data' => ['coupon' => $this->serialize($coupon->fresh())]]);
    }

    public function destroy(Request $request, $id)
    {
        $coupon = $this->ownedCoupon($request, $id);
        if (!$coupon) return $this->notFound();
        $coupon->delete();
        return response()->json(['status' => true, 'message' => 'Coupon deleted successfully']);
    }

    private function ownedCoupon(Request $request, $id): ?Coupon
    {
        return Coupon::whereKey($id)
            ->where('created_by', $request->user()->id)
            ->where('role', 'SUBSCRIBER')
            ->first();
    }

    private function editableCoupon(Request $request, $id): ?Coupon
    {
        $coupon = $this->ownedCoupon($request, $id);
        if ($coupon) return $coupon;

        $pincodeIds = $this->vendorPincodeIds($request->user());
        if (empty($pincodeIds)) return null;

        return Coupon::whereKey($id)
            ->where(function ($creator) {
                $creator->whereNull('role')
                    ->orWhere('role', '!=', 'SUBSCRIBER');
            })
            ->where('type', 2)
            ->whereIn('pincode_id', $pincodeIds)
            ->first();
    }

    /**
     * Coupons visible to a vendor:
     * - coupons created by the authenticated vendor;
     * - admin-created global coupons;
     * - admin-created pincode coupons for the vendor's assigned pincodes.
     *
     * Admin-created global coupons are read-only; admin-created pincode
     * coupons are editable when their pincode belongs to the vendor.
     */
    private function visibleCoupons(Request $request)
    {
        $vendor = $request->user();
        $pincodeIds = $this->vendorPincodeIds($vendor);

        return Coupon::where(function ($query) use ($vendor, $pincodeIds) {
            $query->where(function ($own) use ($vendor) {
                $own->where('created_by', $vendor->id)
                    ->where('role', 'SUBSCRIBER');
            });

            $query->orWhere(function ($admin) use ($pincodeIds) {
                $admin->where(function ($creator) {
                    $creator->whereNull('role')
                        ->orWhere('role', '!=', 'SUBSCRIBER');
                })->where(function ($scope) use ($pincodeIds) {
                    $scope->where('type', 1);
                    if (!empty($pincodeIds)) {
                        $scope->orWhere(function ($pincode) use ($pincodeIds) {
                            $pincode->where('type', 2)
                                ->whereIn('pincode_id', $pincodeIds);
                        });
                    }
                });
            });
        });
    }

    private function vendorPincodeIds($vendor): array
    {
        $pincodeIds = json_decode((string) $vendor->pincode, true);

        return is_array($pincodeIds)
            ? array_values(array_filter(array_map('intval', $pincodeIds)))
            : [];
    }

    private function rules(Request $request, ?Coupon $coupon = null): array
    {
        $required = $coupon ? 'sometimes' : 'required';
        return [
            'title' => $required . '|string|max:255', 'image' => 'sometimes|nullable|string|max:255',
            'type' => $required . '|integer', 'pincode_id' => [$request->input('type') == 2 ? $required : 'nullable', 'integer', 'nullable', Rule::exists('pincode', 'id')],
            'code' => [$required, 'string', 'max:100', Rule::unique('coupons', 'code')->ignore($coupon?->id)],
            'limit' => $required . '|integer|min:0', 'is_multiple' => $required . '|integer|in:0,1',
            'start_date' => $required . '|date', 'expiry_date' => $required . '|date|after_or_equal:start_date',
            'discount_type' => $required . '|integer', 'amount' => 'sometimes|nullable|numeric|min:0',
            'percentage' => 'sometimes|nullable|numeric|min:0|max:100', 'status' => 'sometimes|boolean',
        ];
    }

    private function serialize(Coupon $coupon): array
    {
        return ['id' => (int) $coupon->id, 'title' => $coupon->title, 'image' => $coupon->image,
            'type' => (int) $coupon->type, 'pincode_id' => $coupon->pincode_id ? (int) $coupon->pincode_id : null,
            'code' => $coupon->code, 'limit' => (int) $coupon->limit, 'is_multiple' => (int) $coupon->is_multiple,
            'start_date' => $coupon->start_date?->format('Y-m-d'), 'expiry_date' => $coupon->expiry_date?->format('Y-m-d'),
            'discount_type' => (int) $coupon->discount_type, 'amount' => $coupon->amount, 'percentage' => $coupon->percentage,
            'status' => (int) $coupon->status, 'created_by' => (int) $coupon->created_by];
    }

    private function validationError($validator)
    {
        return response()->json(['status' => false, 'message' => 'Validation error', 'errors' => $validator->errors()], 422);
    }

    private function pincodeOwnershipError(Request $request, array $data)
    {
        $type = $data['type'] ?? null;
        if ($type === null && !empty($data['pincode_id'])) {
            $type = 2;
        }
        if ($type != 2 || !array_key_exists('pincode_id', $data) || $data['pincode_id'] === null) {
            return null;
        }

        $vendorPincodes = $this->vendorPincodeIds($request->user());
        if (!in_array((int) $data['pincode_id'], $vendorPincodes, true)) {
            return response()->json(['status' => false, 'message' => 'Validation error', 'errors' => [
                'pincode_id' => ['Selected pincode does not belong to your vendor account.'],
            ]], 422);
        }
        return null;
    }

    private function notFound()
    {
        return response()->json(['status' => false, 'message' => 'Coupon not found or access denied.'], 404);
    }
    /**
     * Active Coupons for Vendor
     */
    public function active(Request $request)
    {
        $coupons = $this->visibleCoupons($request)
            ->where('status', 1)
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
        $query = $this->visibleCoupons($request);

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
