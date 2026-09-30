<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PricingController extends Controller
{
    private const FIELDS = [
        'bike_taxi_service_fare' => 'biketaxi_price',
        'pickup_and_drop_service_fare' => 'pickup_price',
        'buy_and_delivery_service_fare' => 'buy_price',
        'auto_service_fare' => 'auto_price',
        'cab_service_fare' => 'cab_price',
        'bike_taxi_1_to_5_km' => 'bt_price1',
        'bike_taxi_5_to_8_km' => 'bt_price2',
        'bike_taxi_8_to_10_km' => 'bt_price3',
        'bike_taxi_above_10_km' => 'bt_price4',
        'pickup_drop_1_to_5_km' => 'pk_price1',
        'pickup_drop_5_to_8_km' => 'pk_price2',
        'pickup_drop_8_to_10_km' => 'pk_price3',
        'pickup_drop_above_10_km' => 'pk_price4',
        'buy_delivery_1_to_5_km' => 'bd_price1',
        'buy_delivery_5_to_8_km' => 'bd_price2',
        'buy_delivery_8_to_10_km' => 'bd_price3',
        'buy_delivery_above_10_km' => 'bd_price4',
        'auto_1_to_5_km' => 'at_price1',
        'auto_5_to_8_km' => 'at_price2',
        'auto_8_to_10_km' => 'at_price3',
        'auto_above_10_km' => 'at_price4',
        'cab_1_to_5_km' => 'cab_price1',
        'cab_5_to_8_km' => 'cab_price2',
        'cab_8_to_10_km' => 'cab_price3',
        'cab_above_10_km' => 'cab_price4',
    ];

    public function show(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Pricing retrieved successfully',
            'data' => $this->serialize($request->user()),
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), array_fill_keys(array_keys(self::FIELDS), 'sometimes|numeric|min:0'));

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $vendor = $request->user();
        foreach (self::FIELDS as $apiField => $column) {
            if ($request->has($apiField)) {
                $vendor->{$column} = $request->input($apiField);
            }
        }
        $vendor->save();

        return response()->json([
            'status' => true,
            'message' => 'Pricing updated successfully',
            'data' => $this->serialize($vendor->fresh()),
        ]);
    }

    private function serialize($vendor): array
    {
        $data = [];
        foreach (self::FIELDS as $apiField => $column) {
            $data[$apiField] = $vendor->{$column};
        }
        return $data;
    }
}
