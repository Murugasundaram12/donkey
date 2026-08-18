<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Pincode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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
        $pincodeObjects = Pincode::whereIn('id', $subscribersPin)->get(['id', 'pincode', 'area_name']);

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
                'logo_url' => $vendor->image ? asset('public/subscriber/' . $vendor->image) : null,
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
            'gst' => 'nullable|string|max:100',
            'pincode' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
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
        if ($request->filled('gst')) {
            $vendor->gst = $request->gst;
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

        $vendor->save();

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
}
