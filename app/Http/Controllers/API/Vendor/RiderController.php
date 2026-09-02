<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\User;
use App\Services\VendorNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
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
            'location' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:driver,email|unique:users,email',
            'mobile' => ['required', 'string', 'max:15', 'unique:users,phone', 'unique:driver,mobile'],
            'pincode' => 'required',
            'language' => 'required',
            'password' => 'required|string|min:6',
            'dob' => 'nullable|date',
            'gender' => 'required|string|in:Male,Female,Other,male,female,other',
            'aadharNo' => 'required|numeric|unique:driver,aadharNo',
            'description' => 'nullable|string',
            'bankacno' => 'nullable|string',
            'ifsccode' => 'nullable|string',
            'licenceexpiry' => 'nullable|date',
            'vehicleNo' => 'required|string|max:100',
            'vehicleModelNo' => 'required|string|max:100',
            'type' => 'required',
            'profile' => 'nullable|file|mimes:jpeg,jpg,png,gif,webp,pdf|max:10240',
            'profile_image' => 'nullable|file|mimes:jpeg,jpg,png,gif,webp,pdf|max:10240',
            'aadharFrontImage' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:10240',
            'aadharBackImage' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:10240',
            'drivingLicence' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:10240',
            'rcbook' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:10240',
            'bike' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:10240',
            'customerdocument' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $vendor = $request->user();

        // 1. Pincode Validation: Must belong to authenticated vendor's assigned pincodes
        $vendorPincodes = json_decode($vendor->pincode, true);
        $vendorPincodes = is_array($vendorPincodes) ? array_map('intval', array_values($vendorPincodes)) : [];

        $inputPincodes = $request->pincode;
        if (is_string($inputPincodes)) {
            $decoded = json_decode($inputPincodes, true);
            $inputPincodes = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $inputPincodes)));
        }
        $inputPincodes = is_array($inputPincodes) ? array_map('intval', array_values($inputPincodes)) : [(int)$inputPincodes];

        if (!empty($vendorPincodes)) {
            $invalidPincodes = array_diff($inputPincodes, $vendorPincodes);
            if (!empty($invalidPincodes)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => [
                        'pincode' => ['Selected pincode(s) do not belong to your vendor account.']
                    ]
                ], 422);
            }
        }

        // 2. Format language & service types
        $languageInput = $request->language;
        $languageString = is_array($languageInput) ? implode(',', $languageInput) : (string)$languageInput;

        $typeInput = $request->type;
        $typeString = is_array($typeInput) ? implode(',', $typeInput) : (string)$typeInput;

        // 3. Prepare file upload tracking array for atomic cleanup on transaction failure
        $uploadedFiles = [];

        try {
            $driver = DB::transaction(function () use ($request, $vendor, $inputPincodes, $languageString, $typeString, &$uploadedFiles) {
                // Handle Profile Image Upload
                $profileImageName = null;
                if ($request->hasFile('profile') || $request->hasFile('profile_image')) {
                    $file = $request->file('profile') ?: $request->file('profile_image');
                    $extension = $file->getClientOriginalExtension();
                    $profileImageName = uniqid() . '.' . $extension;
                    $targetPath = public_path('subscriber/driver/profile/' . $profileImageName);
                    $file->move(public_path('subscriber/driver/profile/'), $profileImageName);
                    $uploadedFiles[] = $targetPath;
                }

                $passwordRaw = $request->password;

                // Create User Record for Driver
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->mobile,
                    'password' => Hash::make($passwordRaw),
                    'user_id' => 'DK-' . uniqid(),
                    'is_driver' => 1,
                    'is_live' => 0,
                    'gender' => $request->gender,
                    'dob' => $request->dob,
                    'dop' => $request->dob,
                    'profile_image' => $profileImageName,
                    'image' => $profileImageName,
                    'otp' => rand(1000, 9999),
                ]);

                // Create Driver Record
                $driver = new Driver();
                $driver->subscriberId = $vendor->id; // Enforce vendor ownership
                $driver->userid = $user->id;
                $driver->name = $request->name;
                $driver->location = $request->location;
                $driver->email = $request->email;
                $driver->mobile = $request->mobile;
                $driver->pincode = json_encode($inputPincodes);
                $driver->language = $languageString;
                $driver->source = $passwordRaw;
                $driver->password = Hash::make($passwordRaw);
                $driver->aadharNo = $request->aadharNo;
                $driver->description = $request->description;
                $driver->bankacno = $request->bankacno;
                $driver->ifsccode = $request->ifsccode;
                $driver->licenceexpiry = $request->licenceexpiry;
                $driver->vehicleNo = $request->vehicleNo;
                $driver->vehicleModelNo = $request->vehicleModelNo;
                $driver->type = $typeString;
                $driver->status = 0; // Enforce Pending Approval

                // Document File Uploads & Fallbacks
                $aadharFrontImage = '';
                if ($request->hasFile('aadharFrontImage')) {
                    $aadharFrontImage = time() . '.' . $request->aadharFrontImage->extension();
                    $targetPath = public_path('subscriber/driver/aadhar/' . $aadharFrontImage);
                    $request->aadharFrontImage->move(public_path('subscriber/driver/aadhar'), $aadharFrontImage);
                    $uploadedFiles[] = $targetPath;
                } elseif ($request->filled('aadharFrontImage')) {
                    $aadharFrontImage = $request->input('aadharFrontImage');
                }
                $driver->aadharFrontImage = $aadharFrontImage;

                $aadharBackImage = '';
                if ($request->hasFile('aadharBackImage')) {
                    $aadharBackImage = time() . '.' . $request->aadharBackImage->extension();
                    $targetPath = public_path('subscriber/driver/aadhar/back/' . $aadharBackImage);
                    $request->aadharBackImage->move(public_path('subscriber/driver/aadhar/back'), $aadharBackImage);
                    $uploadedFiles[] = $targetPath;
                } elseif ($request->filled('aadharBackImage')) {
                    $aadharBackImage = $request->input('aadharBackImage');
                }
                $driver->aadharBackImage = $aadharBackImage;

                $drivingLicence = '';
                if ($request->hasFile('drivingLicence')) {
                    $drivingLicence = time() . '.' . $request->drivingLicence->extension();
                    $targetPath = public_path('subscriber/driver/drivingLicence/' . $drivingLicence);
                    $request->drivingLicence->move(public_path('subscriber/driver/drivingLicence'), $drivingLicence);
                    $uploadedFiles[] = $targetPath;
                } elseif ($request->filled('drivingLicence')) {
                    $drivingLicence = $request->input('drivingLicence');
                }
                $driver->drivingLicence = $drivingLicence;

                $rcbook = '';
                if ($request->hasFile('rcbook')) {
                    $rcbook = time() . '.' . $request->rcbook->extension();
                    $targetPath = public_path('subscriber/driver/rcbook/' . $rcbook);
                    $request->rcbook->move(public_path('subscriber/driver/rcbook'), $rcbook);
                    $uploadedFiles[] = $targetPath;
                } elseif ($request->filled('rcbook')) {
                    $rcbook = $request->input('rcbook');
                }
                $driver->rcbook = $rcbook;

                $bike = '';
                if ($request->hasFile('bike')) {
                    $bike = time() . '.' . $request->bike->extension();
                    $targetPath = public_path('subscriber/driver/bike/' . $bike);
                    $request->bike->move(public_path('subscriber/driver/bike'), $bike);
                    $uploadedFiles[] = $targetPath;
                } elseif ($request->filled('bike')) {
                    $bike = $request->input('bike');
                }
                $driver->bike = $bike;

                $customerdocument = '';
                if ($request->hasFile('customerdocument')) {
                    $customerdocument = time() . '.' . $request->customerdocument->extension();
                    $targetPath = public_path('subscriber/driver/document/' . $customerdocument);
                    $request->customerdocument->move(public_path('subscriber/driver/document'), $customerdocument);
                    $uploadedFiles[] = $targetPath;
                } elseif ($request->filled('customerdocument')) {
                    $customerdocument = $request->input('customerdocument');
                }
                $driver->customerdocument = $customerdocument;

                $driver->save();

                return $driver;
            });

            app(VendorNotificationService::class)->create(
                $vendor,
                'Riders',
                'Rider Added Successfully',
                $driver->name . ' has been added successfully.',
                ['event' => 'rider_added', 'rider_id' => (int) $driver->id]
            );

            return response()->json([
                'status' => true,
                'message' => 'Rider created successfully',
                'data' => [
                    'rider' => $this->formatRider($driver)
                ]
            ], 201);
        } catch (\Throwable $e) {
            // Clean up any uploaded files on transaction failure
            foreach ($uploadedFiles as $filePath) {
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }

            return response()->json([
                'status' => false,
                'message' => 'Failed to create rider: ' . $e->getMessage()
            ], 500);
        }
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

        app(VendorNotificationService::class)->create(
            $vendor, 'Riders', 'Rider Approved',
            $rider->name . ' has been approved successfully.',
            ['event' => 'rider_approved', 'rider_id' => (int) $rider->id]
        );

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

        app(VendorNotificationService::class)->create(
            $vendor, 'Riders', 'Rider Rejected',
            $rider->name . ' has been rejected.',
            ['event' => 'rider_rejected', 'rider_id' => (int) $rider->id]
        );

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

        $pincodes = json_decode($r->pincode, true);
        $pincodes = is_array($pincodes) ? array_values($pincodes) : [];

        $user = $r->userid ? User::find($r->userid) : null;
        $profileImage = $user?->image ?: $user?->profile_image;

        return [
            'id' => (int) $r->id,
            'user_id' => (int) $r->userid,
            'subscriber_id' => (int) $r->subscriberId,
            'name' => (string) $r->name,
            'mobile' => (string) $r->mobile,
            'email' => (string) ($r->email ?? ''),
            'location' => (string) ($r->location ?? ''),
            'pincode' => $pincodes,
            'language' => (string) ($r->language ?? ''),
            'vehicle_no' => (string) ($r->vehicleNo ?? ''),
            'vehicle_model_no' => (string) ($r->vehicleModelNo ?? ''),
            'status' => (int) $r->status,
            'status_text' => $statusText,
            'type' => (string) ($r->type ?? ''),
            'gender' => (string) ($user?->gender ?? ''),
            'dob' => $user?->dob ? (string)$user->dob : ($user?->dop ? (string)$user->dop : null),
            'description' => (string) ($r->description ?? ''),
            'bankacno' => (string) ($r->bankacno ?? ''),
            'ifsccode' => (string) ($r->ifsccode ?? ''),
            'licenceexpiry' => (string) ($r->licenceexpiry ?? ''),
            'aadhar_no' => (string) ($r->aadharNo ?? ''),
            'profile_image' => $profileImage ? asset('subscriber/driver/profile/' . $profileImage) : null,
            'aadhar_front_image' => $r->aadharFrontImage ? asset('subscriber/driver/aadhar/' . $r->aadharFrontImage) : null,
            'aadhar_back_image' => $r->aadharBackImage ? asset('subscriber/driver/aadhar/back/' . $r->aadharBackImage) : null,
            'driving_licence' => $r->drivingLicence ? asset('subscriber/driver/drivingLicence/' . $r->drivingLicence) : null,
            'rc_book' => $r->rcbook ? asset('subscriber/driver/rcbook/' . $r->rcbook) : null,
            'bike_image' => $r->bike ? asset('subscriber/driver/bike/' . $r->bike) : null,
            'customer_document' => $r->customerdocument ? asset('subscriber/driver/document/' . $r->customerdocument) : null,
            'created_at' => $r->created_at ? $r->created_at->toDateTimeString() : null,
        ];
    }
}
