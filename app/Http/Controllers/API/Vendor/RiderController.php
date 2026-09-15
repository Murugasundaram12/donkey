<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\User;
use App\Models\Booking;
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
            $statusParam = $request->status;

            if (is_numeric($statusParam)) {
                // Numeric status values: preserve existing behaviour
                $query->where('status', (int) $statusParam);
            } else {
                // Semantic status strings: map explicitly; never cast to int blindly
                switch (strtolower((string) $statusParam)) {
                    case 'pending':
                        // Pending Approval: driver.status = 0
                        $query->where('status', 0);
                        break;

                    case 'approved':
                    case 'active':
                        // Approved / Active: driver.status = 1
                        $query->where('status', 1);
                        break;

                    case 'blocked':
                    case 'rejected':
                        // Blocked / Rejected: driver.status = 2
                        $query->where('status', 2);
                        break;

                    case 'offline':
                        // Offline: approved/active riders whose linked user is NOT live
                        $query->where('status', 1);
                        $approvedUserIds = (clone $query)->pluck('userid')->filter()->values()->all();
                        $onlineUserIds = User::whereIn('id', $approvedUserIds)
                            ->where('is_live', 1)
                            ->pluck('id')
                            ->all();
                        $offlineUserIds = array_diff($approvedUserIds, $onlineUserIds);
                        $query->whereIn('userid', $offlineUserIds);
                        break;

                    case 'engaged':
                        // Engaged (On Trip): approved/active + live + has active booking
                        $query->where('status', 1);
                        $approvedRiders = (clone $query)->get(['id', 'userid']);
                        $identityIds = $approvedRiders->flatMap(fn ($r) => [(string) $r->id, (string) $r->userid])
                            ->filter()->unique()->values()->all();

                        $engagedIdentities = [];
                        if (!empty($identityIds)) {
                            $engagedIdentities = Booking::query()
                                ->where('status', 1)
                                ->where(function ($q) use ($vendor) {
                                    $q->where('assigned_subscriber_id', $vendor->id)
                                      ->orWhere('provider_accepted_by', $vendor->id);
                                })
                                ->where(function ($q) use ($identityIds) {
                                    $q->whereIn('driver_id', $identityIds)
                                      ->orWhereIn('accepted', $identityIds);
                                })
                                ->get(['driver_id', 'accepted'])
                                ->flatMap(fn ($b) => [(string) $b->driver_id, (string) $b->accepted])
                                ->filter()->unique()->values()->all();
                        }

                        // Keep only riders whose id or userid is in the engaged booking set
                        $engagedRiderIds = $approvedRiders->filter(
                            fn ($r) => in_array((string) $r->id, $engagedIdentities, true)
                                    || in_array((string) $r->userid, $engagedIdentities, true)
                        )->pluck('id')->all();

                        $query->whereIn('id', $engagedRiderIds);
                        break;

                    default:
                        // Unknown semantic string: return empty result set safely
                        $query->whereRaw('1 = 0');
                        break;
                }
            }
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
        $vendorPincodes = json_decode((string) $vendor->pincode, true);
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

        $rules = [
            'name' => 'sometimes|required|string|max:255',
            'location' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:driver,email,' . $rider->id . '|unique:users,email,' . ($rider->userid ?: 'NULL'),
            'mobile' => ['sometimes', 'required', 'string', 'max:15', 'unique:driver,mobile,' . $rider->id, 'unique:users,phone,' . ($rider->userid ?: 'NULL')],
            'pincode' => 'sometimes|required',
            'language' => 'sometimes|required',
            'password' => 'nullable|string|min:6',
            'dob' => 'nullable|date',
            'gender' => 'sometimes|required|string|in:Male,Female,Other,male,female,other',
            'aadharNo' => 'sometimes|required|numeric|unique:driver,aadharNo,' . $rider->id,
            'description' => 'nullable|string',
            'bankacno' => 'nullable|string',
            'ifsccode' => 'nullable|string',
            'licenceexpiry' => 'nullable|date',
            'vehicleNo' => 'sometimes|required|string|max:100',
            'vehicleModelNo' => 'sometimes|required|string|max:100',
            'type' => 'sometimes|required',
        ];

        $fileFields = [
            'profile' => 'file|mimes:jpeg,jpg,png,gif,webp,pdf|max:10240',
            'profile_image' => 'file|mimes:jpeg,jpg,png,gif,webp,pdf|max:10240',
            'aadharFrontImage' => 'file|mimes:pdf,jpeg,jpg,png|max:10240',
            'aadharBackImage' => 'file|mimes:pdf,jpeg,jpg,png|max:10240',
            'drivingLicence' => 'file|mimes:pdf,jpeg,jpg,png|max:10240',
            'rcbook' => 'file|mimes:pdf,jpeg,jpg,png|max:10240',
            'bike' => 'file|mimes:pdf,jpeg,jpg,png|max:10240',
            'customerdocument' => 'file|mimes:pdf|max:10240',
            'insurance' => 'file|mimes:pdf,jpeg,jpg,png|max:10240',
            'riderAgreement' => 'file|mimes:pdf,jpeg,jpg,png|max:10240',
        ];

        foreach ($fileFields as $field => $rule) {
            if ($request->hasFile($field)) {
                $rules[$field] = $rule;
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validate pincodes against vendor's assigned pincodes if pincode is being updated
        $inputPincodes = null;
        if ($request->has('pincode')) {
            $vendorPincodes = json_decode((string) $vendor->pincode, true);
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
        }

        $uploadedFiles = [];

        try {
            DB::transaction(function () use ($rider, $request, $inputPincodes, &$uploadedFiles) {
                // Profile Image Upload
                $profileImageName = null;
                if ($request->hasFile('profile') || $request->hasFile('profile_image')) {
                    @mkdir(public_path('subscriber/driver/profile'), 0755, true);
                    $file = $request->file('profile') ?: $request->file('profile_image');
                    $extension = $file->getClientOriginalExtension();
                    $profileImageName = uniqid() . '.' . $extension;
                    $targetPath = public_path('subscriber/driver/profile/' . $profileImageName);
                    $file->move(public_path('subscriber/driver/profile'), $profileImageName);
                    $uploadedFiles[] = $targetPath;
                } elseif ($request->filled('profile') && is_string($request->input('profile'))) {
                    $profileImageName = $request->input('profile');
                } elseif ($request->filled('profile_image') && is_string($request->input('profile_image'))) {
                    $profileImageName = $request->input('profile_image');
                }

                // Driver Documents Upload
                if ($request->hasFile('aadharFrontImage')) {
                    @mkdir(public_path('subscriber/driver/aadhar'), 0755, true);
                    $aadharFrontImage = time() . '.' . $request->aadharFrontImage->extension();
                    $targetPath = public_path('subscriber/driver/aadhar/' . $aadharFrontImage);
                    $request->aadharFrontImage->move(public_path('subscriber/driver/aadhar'), $aadharFrontImage);
                    $uploadedFiles[] = $targetPath;
                    $rider->aadharFrontImage = $aadharFrontImage;
                } elseif ($request->filled('aadharFrontImage') && is_string($request->input('aadharFrontImage'))) {
                    $rider->aadharFrontImage = $request->input('aadharFrontImage');
                }

                if ($request->hasFile('aadharBackImage')) {
                    @mkdir(public_path('subscriber/driver/aadhar/back'), 0755, true);
                    $aadharBackImage = time() . '.' . $request->aadharBackImage->extension();
                    $targetPath = public_path('subscriber/driver/aadhar/back/' . $aadharBackImage);
                    $request->aadharBackImage->move(public_path('subscriber/driver/aadhar/back'), $aadharBackImage);
                    $uploadedFiles[] = $targetPath;
                    $rider->aadharBackImage = $aadharBackImage;
                } elseif ($request->filled('aadharBackImage') && is_string($request->input('aadharBackImage'))) {
                    $rider->aadharBackImage = $request->input('aadharBackImage');
                }

                if ($request->hasFile('drivingLicence')) {
                    @mkdir(public_path('subscriber/driver/drivingLicence'), 0755, true);
                    $drivingLicence = time() . '.' . $request->drivingLicence->extension();
                    $targetPath = public_path('subscriber/driver/drivingLicence/' . $drivingLicence);
                    $request->drivingLicence->move(public_path('subscriber/driver/drivingLicence'), $drivingLicence);
                    $uploadedFiles[] = $targetPath;
                    $rider->drivingLicence = $drivingLicence;
                } elseif ($request->filled('drivingLicence') && is_string($request->input('drivingLicence'))) {
                    $rider->drivingLicence = $request->input('drivingLicence');
                }

                if ($request->hasFile('rcbook')) {
                    @mkdir(public_path('subscriber/driver/rcbook'), 0755, true);
                    $rcbook = time() . '.' . $request->rcbook->extension();
                    $targetPath = public_path('subscriber/driver/rcbook/' . $rcbook);
                    $request->rcbook->move(public_path('subscriber/driver/rcbook'), $rcbook);
                    $uploadedFiles[] = $targetPath;
                    $rider->rcbook = $rcbook;
                } elseif ($request->filled('rcbook') && is_string($request->input('rcbook'))) {
                    $rider->rcbook = $request->input('rcbook');
                }

                if ($request->hasFile('bike')) {
                    @mkdir(public_path('subscriber/driver/bike'), 0755, true);
                    $bike = time() . '.' . $request->bike->extension();
                    $targetPath = public_path('subscriber/driver/bike/' . $bike);
                    $request->bike->move(public_path('subscriber/driver/bike'), $bike);
                    $uploadedFiles[] = $targetPath;
                    $rider->bike = $bike;
                } elseif ($request->filled('bike') && is_string($request->input('bike'))) {
                    $rider->bike = $request->input('bike');
                }

                if ($request->hasFile('customerdocument')) {
                    @mkdir(public_path('subscriber/driver/document'), 0755, true);
                    $customerdocument = time() . '.' . $request->customerdocument->extension();
                    $targetPath = public_path('subscriber/driver/document/' . $customerdocument);
                    $request->customerdocument->move(public_path('subscriber/driver/document'), $customerdocument);
                    $uploadedFiles[] = $targetPath;
                    $rider->customerdocument = $customerdocument;
                } elseif ($request->filled('customerdocument') && is_string($request->input('customerdocument'))) {
                    $rider->customerdocument = $request->input('customerdocument');
                }

                if ($request->hasFile('insurance')) {
                    @mkdir(public_path('subscriber/driver/insurance'), 0755, true);
                    $insurance = time() . '.' . $request->insurance->extension();
                    $targetPath = public_path('subscriber/driver/insurance/' . $insurance);
                    $request->insurance->move(public_path('subscriber/driver/insurance'), $insurance);
                    $uploadedFiles[] = $targetPath;
                    $rider->insurance = $insurance;
                } elseif ($request->filled('insurance') && is_string($request->input('insurance'))) {
                    $rider->insurance = $request->input('insurance');
                }

                if ($request->hasFile('riderAgreement')) {
                    @mkdir(public_path('subscriber/driver/riderAgreement'), 0755, true);
                    $riderAgreement = time() . '.' . $request->riderAgreement->extension();
                    $targetPath = public_path('subscriber/driver/riderAgreement/' . $riderAgreement);
                    $request->riderAgreement->move(public_path('subscriber/driver/riderAgreement'), $riderAgreement);
                    $uploadedFiles[] = $targetPath;
                    $rider->riderAgreement = $riderAgreement;
                } elseif ($request->filled('riderAgreement') && is_string($request->input('riderAgreement'))) {
                    $rider->riderAgreement = $request->input('riderAgreement');
                }

                // Update scalar driver fields
                if ($request->has('name')) {
                    $rider->name = $request->name;
                }
                if ($request->has('location')) {
                    $rider->location = $request->location;
                }
                if ($request->has('email')) {
                    $rider->email = $request->email;
                }
                if ($request->has('mobile')) {
                    $rider->mobile = $request->mobile;
                }
                if ($inputPincodes !== null) {
                    $rider->pincode = json_encode($inputPincodes);
                }
                if ($request->has('language')) {
                    $languageInput = $request->language;
                    $rider->language = is_array($languageInput) ? implode(',', $languageInput) : (string)$languageInput;
                }
                if ($request->has('type')) {
                    $typeInput = $request->type;
                    $rider->type = is_array($typeInput) ? implode(',', $typeInput) : (string)$typeInput;
                }
                if ($request->has('aadharNo')) {
                    $rider->aadharNo = $request->aadharNo;
                }
                if ($request->has('description')) {
                    $rider->description = $request->description;
                }
                if ($request->has('bankacno')) {
                    $rider->bankacno = $request->bankacno;
                }
                if ($request->has('ifsccode')) {
                    $rider->ifsccode = $request->ifsccode;
                }
                if ($request->has('licenceexpiry')) {
                    $rider->licenceexpiry = $request->licenceexpiry;
                }
                if ($request->has('vehicleNo')) {
                    $rider->vehicleNo = $request->vehicleNo;
                }
                if ($request->has('vehicleModelNo')) {
                    $rider->vehicleModelNo = $request->vehicleModelNo;
                }
                if ($request->filled('password')) {
                    $rider->source = $request->password;
                    $rider->password = Hash::make($request->password);
                }

                $rider->save();

                // Update related User record
                if ($rider->userid) {
                    $userUpdates = [];
                    if ($request->has('name')) {
                        $userUpdates['name'] = $request->name;
                    }
                    if ($request->has('mobile')) {
                        $userUpdates['phone'] = $request->mobile;
                    }
                    if ($request->has('email')) {
                        $userUpdates['email'] = $request->email;
                    }
                    if ($request->has('gender')) {
                        $userUpdates['gender'] = $request->gender;
                    }
                    if ($request->has('dob')) {
                        $userUpdates['dob'] = $request->dob;
                        $userUpdates['dop'] = $request->dob;
                    }
                    if ($profileImageName) {
                        $userUpdates['profile_image'] = $profileImageName;
                        $userUpdates['image'] = $profileImageName;
                    }
                    if ($request->filled('password')) {
                        $userUpdates['password'] = Hash::make($request->password);
                    }
                    if (!empty($userUpdates)) {
                        User::where('id', $rider->userid)->update($userUpdates);
                    }
                }
            });
        } catch (\Throwable $e) {
            foreach ($uploadedFiles as $filePath) {
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }

            return response()->json([
                'status' => false,
                'message' => 'Failed to update rider. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }

        app(VendorNotificationService::class)->create(
            $vendor,
            'Riders',
            'Rider Updated Successfully',
            $rider->name . ' has been updated successfully.',
            ['event' => 'rider_updated', 'rider_id' => (int) $rider->id]
        );

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

    /**
     * Vendor rider overview. Online/offline are approved riders only; pending
     * and rejected riders are returned as separate counts so totals reconcile.
     */
    public function overview(Request $request)
    {
        $vendor = $request->user();
        $riders = Driver::where('subscriberId', $vendor->id)->where('status', 1)->get();
        $userIds = $riders->pluck('userid')->filter()->values();
        $liveUsers = User::whereIn('id', $userIds)->where('is_live', 1)->get()->keyBy('id');

        $identityIds = $riders->flatMap(function ($rider) {
            return [(string) $rider->id, (string) $rider->userid];
        })->filter()->unique()->values()->all();

        $engagedIdentities = [];
        if (!empty($identityIds)) {
            $engagedIdentities = Booking::query()
                ->where('status', 1)
                ->where(function ($query) use ($vendor) {
                    $query->where('assigned_subscriber_id', $vendor->id)
                        ->orWhere('provider_accepted_by', $vendor->id);
                })
                ->where(function ($query) use ($identityIds) {
                    $query->whereIn('driver_id', $identityIds)->orWhereIn('accepted', $identityIds);
                })
                ->get(['driver_id', 'accepted'])
                ->flatMap(fn ($booking) => [(string) $booking->driver_id, (string) $booking->accepted])
                ->filter()->unique()->values()->all();
        }

        $isEngaged = fn ($rider) => in_array((string) $rider->id, $engagedIdentities, true)
            || in_array((string) $rider->userid, $engagedIdentities, true);
        $online = $riders->filter(fn ($rider) => $liveUsers->has($rider->userid))->values();
        $engaged = $online->filter($isEngaged)->values();
        $offline = $riders->reject(fn ($rider) => $liveUsers->has($rider->userid))->values();

        $formatList = function ($items, $status) {
            return $items->map(function ($rider) use ($status) {
                $item = $this->formatRider($rider);
                $item['online_status'] = $status;
                return $item;
            })->values();
        };

        return response()->json([
            'status' => true,
            'message' => 'Rider overview retrieved successfully',
            'data' => [
                'total_riders' => $riders->count(),
                'online_riders' => $online->count(),
                'engaged_riders' => $engaged->count(),
                'offline_riders' => $offline->count(),
                'pending_approval_riders' => Driver::where('subscriberId', $vendor->id)->where('status', 0)->count(),
                'online' => $formatList($online, 'Online'),
                'engaged' => $formatList($engaged, 'On Trip'),
                'offline' => $formatList($offline, 'Offline'),
            ],
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

        $pincodes = json_decode((string) $r->pincode, true);
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
            'profile_image' => $this->resolveRiderDocumentUrl($profileImage, 'subscriber/driver/profile'),
            'aadhar_front_image' => $this->resolveRiderDocumentUrl($r->aadharFrontImage, 'subscriber/driver/aadhar'),
            'aadhar_back_image' => $this->resolveRiderDocumentUrl($r->aadharBackImage, 'subscriber/driver/aadhar/back'),
            'driving_licence' => $this->resolveRiderDocumentUrl($r->drivingLicence, 'subscriber/driver/drivingLicence'),
            'rc_book' => $this->resolveRiderDocumentUrl($r->rcbook, 'subscriber/driver/rcbook'),
            'bike_image' => $this->resolveRiderDocumentUrl($r->bike, 'subscriber/driver/bike'),
            'customer_document' => $this->resolveRiderDocumentUrl($r->customerdocument, 'subscriber/driver/document'),
            'insurance' => $this->resolveRiderDocumentUrl($r->insurance, 'subscriber/driver/insurance'),
            'rider_agreement' => $this->resolveRiderDocumentUrl($r->riderAgreement, 'subscriber/driver/riderAgreement'),
            'created_at' => $r->created_at ? $r->created_at->toDateTimeString() : null,
        ];
    }

    /**
     * Resolve Public Rider Document URL only if the physical file exists.
     */
    private function resolveRiderDocumentUrl(?string $filename, string $relativePath): ?string
    {
        if (empty($filename)) {
            return null;
        }

        $safeFilename = basename($filename);

        if ($safeFilename === '.' || $safeFilename === '..' || empty($safeFilename)) {
            return null;
        }

        // Rider document/image values must represent an actual filename.
        if (!str_contains($safeFilename, '.')) {
            return null;
        }

        $cleanRelativePath = trim($relativePath, '/');

        $candidatePath = public_path(
            $cleanRelativePath . '/' . $safeFilename
        );

        if (file_exists($candidatePath) && is_file($candidatePath)) {
            return asset($cleanRelativePath . '/' . $safeFilename);
        }

        return null;
    }
}
