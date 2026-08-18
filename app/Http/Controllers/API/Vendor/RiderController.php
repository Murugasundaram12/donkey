<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RiderController extends Controller
{
    /**
     * List Vendor Riders
     */
    public function index(Request $request)
    {
        $vendor = $request->user();

        $query = Driver::where('subscriberId', $vendor->id);

        if ($request->has('status') && $request->status !== null && $request->status !== '') {
            $query->where('status', (int) $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('vehicleNo', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->get('per_page', 15);
        $riders = $query->orderBy('created_at', 'desc')->paginate($perPage);

        $formatted = collect($riders->items())->map(function ($rider) {
            return $this->formatRider($rider);
        });

        return response()->json([
            'status' => true,
            'message' => 'Riders retrieved successfully',
            'data' => [
                'current_page' => $riders->currentPage(),
                'per_page' => $riders->perPage(),
                'total' => $riders->total(),
                'last_page' => $riders->lastPage(),
                'items' => $formatted,
            ]
        ]);
    }

    /**
     * Add New Rider Under Vendor
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15|unique:driver,mobile|unique:users,phone',
            'email' => 'nullable|email|unique:driver,email|unique:users,email',
            'password' => 'required|string|min:6',
            'pincode' => 'required',
            'vehicleNo' => 'required|string|max:100',
            'vehicleModelNo' => 'required|string|max:100',
            'aadharNo' => 'required|string|unique:driver,aadharNo',
            'drivingLicence' => 'required|string',
            'rcbook' => 'required|string',
            'gender' => 'nullable|string',
            'location' => 'nullable|string',
            'type' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $vendor = $request->user();

        // Handle types (e.g. array or string)
        $type = $request->type;
        if (is_array($type)) {
            $type = implode(',', $type);
        }

        // Create User account for driver
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->mobile,
            'password' => Hash::make($request->password),
            'user_id' => 'DK-' . uniqid(),
            'is_driver' => 1,
            'is_live' => 0,
            'gender' => $request->gender ?: 'male',
            'otp' => rand(1000, 9999),
        ]);

        $pincodes = is_array($request->pincode) ? json_encode($request->pincode) : $request->pincode;

        $driver = new Driver();
        $driver->subscriberId = $vendor->id;
        $driver->userid = $user->id;
        $driver->name = $request->name;
        $driver->mobile = $request->mobile;
        $driver->email = $request->email;
        $driver->password = Hash::make($request->password);
        $driver->source = $request->password;
        $driver->pincode = $pincodes;
        $driver->location = $request->location;
        $driver->vehicleNo = $request->vehicleNo;
        $driver->vehicleModelNo = $request->vehicleModelNo;
        $driver->aadharNo = $request->aadharNo;
        $driver->drivingLicence = $request->drivingLicence;
        $driver->rcbook = $request->rcbook;
        $driver->aadharFrontImage = $request->input('aadharFrontImage', 'default_front.jpg');
        $driver->aadharBackImage = $request->input('aadharBackImage', 'default_back.jpg');
        $driver->type = $type ?: '1';
        $driver->status = 1; // Auto-active when added by vendor
        $driver->save();

        return response()->json([
            'status' => true,
            'message' => 'Rider created successfully',
            'data' => [
                'rider' => $this->formatRider($driver)
            ]
        ], 201);
    }

    /**
     * Show Rider Details
     */
    public function show(Request $request, $id)
    {
        $vendor = $request->user();
        $rider = Driver::where('subscriberId', $vendor->id)->where('id', $id)->first();

        if (!$rider) {
            return response()->json([
                'status' => false,
                'message' => 'Rider not found or access denied.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Rider details retrieved successfully',
            'data' => [
                'rider' => $this->formatRider($rider)
            ]
        ]);
    }

    /**
     * Update Rider Details
     */
    public function update(Request $request, $id)
    {
        $vendor = $request->user();
        $rider = Driver::where('subscriberId', $vendor->id)->where('id', $id)->first();

        if (!$rider) {
            return response()->json([
                'status' => false,
                'message' => 'Rider not found or access denied.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:15|unique:driver,mobile,' . $rider->id . '|unique:users,phone,' . $rider->userid,
            'email' => 'nullable|email|unique:driver,email,' . $rider->id . '|unique:users,email,' . $rider->userid,
            'vehicleNo' => 'nullable|string|max:100',
            'vehicleModelNo' => 'nullable|string|max:100',
            'location' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->filled('name')) {
            $rider->name = $request->name;
        }
        if ($request->filled('mobile')) {
            $rider->mobile = $request->mobile;
        }
        if ($request->filled('email')) {
            $rider->email = $request->email;
        }
        if ($request->filled('vehicleNo')) {
            $rider->vehicleNo = $request->vehicleNo;
        }
        if ($request->filled('vehicleModelNo')) {
            $rider->vehicleModelNo = $request->vehicleModelNo;
        }
        if ($request->filled('location')) {
            $rider->location = $request->location;
        }
        $rider->save();

        if ($rider->userid) {
            User::where('id', $rider->userid)->update(array_filter([
                'name' => $request->name,
                'phone' => $request->mobile,
                'email' => $request->email,
            ]));
        }

        return response()->json([
            'status' => true,
            'message' => 'Rider updated successfully',
            'data' => [
                'rider' => $this->formatRider($rider->fresh())
            ]
        ]);
    }

    /**
     * Delete Rider
     */
    public function destroy(Request $request, $id)
    {
        $vendor = $request->user();
        $rider = Driver::where('subscriberId', $vendor->id)->where('id', $id)->first();

        if (!$rider) {
            return response()->json([
                'status' => false,
                'message' => 'Rider not found or access denied.'
            ], 404);
        }

        $rider->delete();

        return response()->json([
            'status' => true,
            'message' => 'Rider removed successfully'
        ]);
    }

    /**
     * Pending Approvals
     */
    public function approvals(Request $request)
    {
        $vendor = $request->user();
        $riders = Driver::where('subscriberId', $vendor->id)
            ->where('status', 0)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($r) {
                return $this->formatRider($r);
            });

        return response()->json([
            'status' => true,
            'message' => 'Pending rider approvals retrieved',
            'data' => [
                'items' => $riders
            ]
        ]);
    }

    /**
     * Approve Rider
     */
    public function approve(Request $request, $id)
    {
        $vendor = $request->user();
        $rider = Driver::where('subscriberId', $vendor->id)->where('id', $id)->first();

        if (!$rider) {
            return response()->json([
                'status' => false,
                'message' => 'Rider not found or access denied.'
            ], 404);
        }

        $rider->status = 1;
        $rider->save();

        return response()->json([
            'status' => true,
            'message' => 'Rider approved successfully',
            'data' => [
                'rider' => $this->formatRider($rider)
            ]
        ]);
    }

    /**
     * Reject / Block Rider
     */
    public function reject(Request $request, $id)
    {
        $vendor = $request->user();
        $rider = Driver::where('subscriberId', $vendor->id)->where('id', $id)->first();

        if (!$rider) {
            return response()->json([
                'status' => false,
                'message' => 'Rider not found or access denied.'
            ], 404);
        }

        $rider->status = 2; // Blocked / Rejected
        $rider->save();

        return response()->json([
            'status' => true,
            'message' => 'Rider rejected/blocked successfully',
            'data' => [
                'rider' => $this->formatRider($rider)
            ]
        ]);
    }

    /**
     * Online Riders
     */
    public function online(Request $request)
    {
        $vendor = $request->user();
        $driverUserIds = Driver::where('subscriberId', $vendor->id)
            ->pluck('userid')
            ->filter()
            ->toArray();

        $onlineUsers = User::whereIn('id', $driverUserIds)
            ->where('is_live', 1)
            ->get();

        $riders = Driver::where('subscriberId', $vendor->id)
            ->whereIn('userid', $onlineUsers->pluck('id')->toArray())
            ->get()
            ->map(function ($r) use ($onlineUsers) {
                $user = $onlineUsers->firstWhere('id', $r->userid);
                $res = $this->formatRider($r);
                $res['is_live'] = 1;
                $res['lat'] = $r->lat;
                $res['long'] = $r->long;
                return $res;
            });

        return response()->json([
            'status' => true,
            'message' => 'Online riders retrieved successfully',
            'data' => [
                'total_online' => count($riders),
                'items' => $riders
            ]
        ]);
    }

    private function formatRider($r): array
    {
        $statusText = match ((int) $r->status) {
            0 => 'Pending Approval',
            1 => 'Active',
            2 => 'Blocked/Rejected',
            default => 'Unknown',
        };

        return [
            'id' => (int) $r->id,
            'user_id' => (int) $r->userid,
            'subscriber_id' => (int) $r->subscriberId,
            'name' => (string) $r->name,
            'mobile' => (string) $r->mobile,
            'email' => (string) ($r->email ?? ''),
            'location' => (string) ($r->location ?? ''),
            'vehicle_no' => (string) ($r->vehicleNo ?? ''),
            'vehicle_model_no' => (string) ($r->vehicleModelNo ?? ''),
            'status' => (int) $r->status,
            'status_text' => $statusText,
            'type' => (string) ($r->type ?? ''),
            'aadhar_no' => (string) ($r->aadharNo ?? ''),
            'driving_licence' => (string) ($r->drivingLicence ?? ''),
            'rc_book' => (string) ($r->rcbook ?? ''),
            'created_at' => $r->created_at ? $r->created_at->toDateTimeString() : null,
        ];
    }
}
