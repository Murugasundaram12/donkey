<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Pincode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BusinessController extends Controller
{
    /**
     * Get Business Information
     */
    public function getBusiness(Request $request)
    {
        $vendor = $request->user();

        $subscribersPin = json_decode($vendor->pincode, true);
        $subscribersPin = is_array($subscribersPin) ? array_values($subscribersPin) : [];
        $pincodeCols = ['id', 'pincode'];
        if (\Illuminate\Support\Facades\Schema::hasColumn('pincode', 'area_name')) {
            $pincodeCols[] = 'area_name';
        } elseif (\Illuminate\Support\Facades\Schema::hasColumn('pincode', 'district')) {
            $pincodeCols[] = 'district as area_name';
        }
        $pincodeObjects = Pincode::whereIn('id', $subscribersPin)->get($pincodeCols);

        return response()->json([
            'status' => true,
            'message' => 'Business information retrieved successfully',
            'data' => [
                'id' => (int) $vendor->id,
                'subscriber_id' => (string) $vendor->subscriberId,
                'business_name' => (string) $vendor->name,
                'email' => (string) $vendor->email,
                'phone' => (string) $vendor->mobile,
                'location' => (string) ($vendor->location ?? ''),
                'description' => (string) ($vendor->description ?? ''),
                'gst' => (string) ($vendor->gst ?? ''),
                'pincodes' => $pincodeObjects,
                // 'logo_url' => $vendor->image ? asset('public/subscriber/' . $vendor->image) : null,
                'qr_code_url' => $this->resolveQrUrl($vendor->qr),
                'created_at' => $vendor->created_at ? $vendor->created_at->toDateTimeString() : null,
            ]
        ]);
    }

    /**
     * Update Business Information
     */
    public function updateBusiness(Request $request)
    {
        $vendor = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:subscriber,email,' . $vendor->id,
            'mobile' => 'nullable|string|max:15|unique:subscriber,mobile,' . $vendor->id,
            'location' => 'nullable|string',
            'gst' => ['nullable', 'string', 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i'],
            'pincode' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'qr' => 'nullable|file|image|mimes:jpeg,png,jpg|max:4096',
        ], [
            'gst.regex' => 'The gst format is invalid. Please enter a valid 15-character GSTIN (e.g. 33AAAAA0000A1Z5).'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->filled('name')) {
            $vendor->name = $request->name;
        }
        if ($request->filled('email')) {
            $vendor->email = $request->email;
        }
        if ($request->filled('mobile')) {
            $vendor->mobile = $request->mobile;
        }
        if ($request->filled('location')) {
            $vendor->location = $request->location;
        }
        if ($request->has('gst')) {
            $gstValue = $request->input('gst');
            $vendor->gst = ($gstValue !== null && trim((string) $gstValue) !== '') ? strtoupper(trim((string) $gstValue)) : null;
        }
        if ($request->has('pincode')) {
            $pincodes = is_array($request->pincode) ? json_encode($request->pincode) : $request->pincode;
            $vendor->pincode = $pincodes;
        }

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('subscriber'), $imageName);
            $vendor->image = $imageName;
        }

        $oldQrFilename = $vendor->qr;
        $newQrUploaded = false;

        if ($request->hasFile('qr')) {
            $qrFile = $request->file('qr');
            $extension = $qrFile->getClientOriginalExtension() ?: $qrFile->extension();
            $qrName = time() . '_qr_' . uniqid() . '.' . $extension;

            $qrDir = public_path('qr');
            if (!File::isDirectory($qrDir)) {
                File::makeDirectory($qrDir, 0775, true, true);
            }

            $qrFile->move($qrDir, $qrName);
            $vendor->qr = $qrName;
            $newQrUploaded = true;
        }

        $vendor->save();

        if ($newQrUploaded && !empty($oldQrFilename) && $oldQrFilename !== $vendor->qr) {
            $this->deleteOldQrFile($oldQrFilename);
        }

        return response()->json([
            'status' => true,
            'message' => 'Business information updated successfully',
            'data' => [
                'id' => (int) $vendor->id,
                'business_name' => (string) $vendor->name,
                'email' => (string) $vendor->email,
                'phone' => (string) $vendor->mobile,
                'location' => (string) ($vendor->location ?? ''),
                'gst' => (string) ($vendor->gst ?? ''),
                'logo_url' => $vendor->image ? asset('public/subscriber/' . $vendor->image) : null,
                'qr_code_url' => $this->resolveQrUrl($vendor->qr),
            ]
        ]);
    }

    /**
     * Get Work Description & Services Pricing
     */
    public function getWorkDescription(Request $request)
    {
        $vendor = $request->user();

        return response()->json([
            'status' => true,
            'message' => 'Work description retrieved successfully',
            'data' => [
                'description' => (string) ($vendor->description ?? ''),
                'service_base_prices' => [
                    'biketaxi_price' => (float) ($vendor->biketaxi_price ?? 0),
                    'pickup_price' => (float) ($vendor->pickup_price ?? 0),
                    'buy_price' => (float) ($vendor->buy_price ?? 0),
                    'auto_price' => (float) ($vendor->auto_price ?? 0),
                    'cab_price' => (float) ($vendor->cab_price ?? 0),
                ],
                'distance_tiers' => [
                    'bike_taxi' => [
                        'tier_1' => (float) ($vendor->bt_price1 ?? 0),
                        'tier_2' => (float) ($vendor->bt_price2 ?? 0),
                        'tier_3' => (float) ($vendor->bt_price3 ?? 0),
                        'tier_4' => (float) ($vendor->bt_price4 ?? 0),
                    ],
                    'pickup' => [
                        'tier_1' => (float) ($vendor->pk_price1 ?? 0),
                        'tier_2' => (float) ($vendor->pk_price2 ?? 0),
                        'tier_3' => (float) ($vendor->pk_price3 ?? 0),
                        'tier_4' => (float) ($vendor->pk_price4 ?? 0),
                    ],
                    'buy_and_delivery' => [
                        'tier_1' => (float) ($vendor->bd_price1 ?? 0),
                        'tier_2' => (float) ($vendor->bd_price2 ?? 0),
                        'tier_3' => (float) ($vendor->bd_price3 ?? 0),
                        'tier_4' => (float) ($vendor->bd_price4 ?? 0),
                    ],
                    'auto' => [
                        'tier_1' => (float) ($vendor->at_price1 ?? 0),
                        'tier_2' => (float) ($vendor->at_price2 ?? 0),
                        'tier_3' => (float) ($vendor->at_price3 ?? 0),
                        'tier_4' => (float) ($vendor->at_price4 ?? 0),
                    ],
                    'cab' => [
                        'tier_1' => (float) ($vendor->cab_price1 ?? 0),
                        'tier_2' => (float) ($vendor->cab_price2 ?? 0),
                        'tier_3' => (float) ($vendor->cab_price3 ?? 0),
                        'tier_4' => (float) ($vendor->cab_price4 ?? 0),
                    ],
                ]
            ]
        ]);
    }

    /**
     * Update Work Description & Services Pricing
     */
    public function updateWorkDescription(Request $request)
    {
        $vendor = $request->user();

        $validator = Validator::make($request->all(), [
            'description' => 'nullable|string',
            'biketaxi_price' => 'nullable|numeric',
            'pickup_price' => 'nullable|numeric',
            'buy_price' => 'nullable|numeric',
            'auto_price' => 'nullable|numeric',
            'cab_price' => 'nullable|numeric',
            'bt_price1' => 'nullable|numeric',
            'bt_price2' => 'nullable|numeric',
            'bt_price3' => 'nullable|numeric',
            'bt_price4' => 'nullable|numeric',
            'pk_price1' => 'nullable|numeric',
            'pk_price2' => 'nullable|numeric',
            'pk_price3' => 'nullable|numeric',
            'pk_price4' => 'nullable|numeric',
            'bd_price1' => 'nullable|numeric',
            'bd_price2' => 'nullable|numeric',
            'bd_price3' => 'nullable|numeric',
            'bd_price4' => 'nullable|numeric',
            'at_price1' => 'nullable|numeric',
            'at_price2' => 'nullable|numeric',
            'at_price3' => 'nullable|numeric',
            'at_price4' => 'nullable|numeric',
            'cab_price1' => 'nullable|numeric',
            'cab_price2' => 'nullable|numeric',
            'cab_price3' => 'nullable|numeric',
            'cab_price4' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $fields = [
            'description', 'biketaxi_price', 'pickup_price', 'buy_price', 'auto_price', 'cab_price',
            'bt_price1', 'bt_price2', 'bt_price3', 'bt_price4',
            'pk_price1', 'pk_price2', 'pk_price3', 'pk_price4',
            'bd_price1', 'bd_price2', 'bd_price3', 'bd_price4',
            'at_price1', 'at_price2', 'at_price3', 'at_price4',
            'cab_price1', 'cab_price2', 'cab_price3', 'cab_price4',
        ];

        foreach ($fields as $f) {
            if ($request->has($f)) {
                $vendor->$f = $request->input($f);
            }
        }

        $vendor->save();

        return response()->json([
            'status' => true,
            'message' => 'Work description updated successfully'
        ]);
    }

    /**
     * Resolve QR code image URL across existing storage conventions.
     *
     * @param string|null $filename
     * @return string|null
     */
    public function resolveQrUrl(?string $filename): ?string
    {
        if (empty($filename)) {
            return null;
        }

        if (str_starts_with($filename, 'http://') || str_starts_with($filename, 'https://')) {
            return $filename;
        }

        $safeFilename = basename($filename);
        if ($safeFilename === '.' || $safeFilename === '..' || empty($safeFilename)) {
            return null;
        }

        $candidateDirs = [
            'qr',
            'subscriber/qr',
            'admin/subscriber/qr',
        ];

        foreach ($candidateDirs as $dir) {
            $candidatePath = public_path($dir . '/' . $safeFilename);
            if (File::exists($candidatePath) && File::isFile($candidatePath)) {
                return asset($dir . '/' . $safeFilename);
            }
        }

        return asset('qr/' . $safeFilename);
    }

    /**
     * Safely delete old QR file from disk after successful update.
     *
     * @param string|null $filename
     * @return void
     */
    protected function deleteOldQrFile(?string $filename): void
    {
        if (empty($filename)) {
            return;
        }

        $safeFilename = basename($filename);
        if ($safeFilename === '.' || $safeFilename === '..' || empty($safeFilename)) {
            return;
        }

        $candidateDirs = [
            'qr',
            'subscriber/qr',
            'admin/subscriber/qr',
        ];

        foreach ($candidateDirs as $dir) {
            $candidatePath = public_path($dir . '/' . $safeFilename);
            if (File::exists($candidatePath) && File::isFile($candidatePath)) {
                @unlink($candidatePath);
                break;
            }
        }
    }
}
