<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;

class Booking extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $action)
    {
        switch ($action) {
            case 'list':
                $validator = Validator::make($request->all(), [
                    'user_id' => 'required'
                ]);
                $data = $request->all();
                $user_id = isset($data['user_id']) ? $data['user_id'] : null;
                if ($validator->fails()) {
                    return $this->sendError('Validation Error.', $validator->errors());
                }
                $booking_list = $this->bookingList($user_id);
                if ($booking_list) {
                    return $this->sendResponse($booking_list, 'success.');
                } else {
                    return $this->sendError('Error.', ['error' => 'something went wrong']);
                }
                break;
            case 'cancel':
                $validator = Validator::make($request->all(), [
                    'user_id' => 'required'
                ]);
                $data = $request->all();
                $user_id = isset($data['user_id']) ? $data['user_id'] : null;
                $booking_id = isset($data['booking_id']) ? $data['booking_id'] : null;
                if ($validator->fails()) {
                    return $this->sendError('Validation Error.', $validator->errors());
                }
                $update = DB::table('booking')->where(['booking_id' => $booking_id, 'customer_id' => $user_id])->update(['status' => 3]);
                $update_history = [
                    "booking_id" => $booking_id,
                    "action" => "BOOKING CANCELLED_BY_USER",
                    "user_id" => $user_id
                ];
                DB::table('booking_history')->insert($update_history);
                if ($update) {
                    return $this->sendResponse(null, 'booking cancelled');
                } else {
                    return $this->sendError('Error.', ['error' => 'something went wrong']);
                }
                break;
            case 'details':
                $validator = Validator::make($request->all(), [
                    'user_id' => 'required'
                ]);
                $data = $request->all();
                $user_id = isset($data['user_id']) ? $data['user_id'] : null;
                $booking_id = isset($data['booking_id']) ? $data['booking_id'] : null;
                if ($validator->fails()) {
                    return $this->sendError('Validation Error.', $validator->errors());
                }
                $booking_list = $this->bookingList($user_id, $booking_id);
                if ($booking_list) {
                    return $this->sendResponse($booking_list, 'success.');
                } else {
                    return $this->sendError('Error.', ['error' => 'something went wrong']);
                }
                break;
            case 'calculation':
                $input = $request->all();
                $source = isset($input['source']) ? (string) $input['source'] : '0';

                $rules = [
                    'distance' => 'required',
                    'category' => 'required',
                    'pincode' => 'required'
                ];

                if ($source === '0') {
                    $rules['user_id'] = 'required';
                } else {
                    $rules['company_id'] = 'required';
                    $rules['external_phone'] = 'required';
                    $rules['user_id'] = 'nullable';  // Allow user_id even for source=1
                }

                $validator = Validator::make($input, $rules);
                if ($validator->fails()) {
                    return $this->sendError('Validation Error.', $validator->errors());
                }

                $category = $input['category'];
                $distance = $input['distance'];
                $pincode = $input['pincode'];
                $res_data = $this->calculation($category, $distance, $pincode);
                if ($res_data) {
                    return $this->sendResponse($res_data, 'success.');
                } else {
                    return $this->sendError('Error.', ['error' => 'something went wrong']);
                }
                break;
            case 'create':
                $input = $request->all();
                $source = $input['source'] ?? 0;

                $rules = [
                    'source' => 'sometimes|in:0,1',
                    'distance' => 'required',
                    'category' => 'required',
                    'from_location' => 'required',
                    'to_location' => 'required',
                    'pincode' => 'required'
                ];

                if ((string)$source === '1') {
                    $rules['company_id'] = 'required|exists:companies,id';
                    $rules['external_phone'] = 'required|string';
                } else {
                    $rules['user_id'] = 'required';
                }

                $validator = Validator::make($input, $rules);
                if ($validator->fails()) {
                    return $this->sendError('Validation Error.', $validator->errors());
                }

                try {
                    $booking_id = DB::transaction(function () use ($input, $source) {
                        return $this->create($input, $source);
                    });
                    return $this->sendResponse($booking_id, 'Booking created successfully.');
                } catch (\Exception $e) {
                    return $this->sendError('Booking creation failed.', ['error' => $e->getMessage()]);
                }
                break;
            default:
                break;
        }
    }
    public function bookingList($user_id, $booking_id = null)
    {
        $booking_data = DB::table('booking')->where('customer_id', '=', $user_id)->select('driver_id', 'booking_id', 'customer_id', 'payment_id', 'status', 'category', 'distance', 'pincode');
        if ($booking_id) {
            $booking_data = $booking_data->where('booking_id', $booking_id);
        }
        $booking_data = $booking_data->get()->toArray();
        $result = [];
        if ($booking_data) {
            $booking_location_data = $location_payment_data = $driver_data = [];
            $booking_ids = array_column($booking_data, 'booking_id');
            $driver_array = array_column($booking_data, 'driver');
            if ($driver_array) {
                $driver_result = DB::table('driver')->whereIn('id', $driver_array)->select('id', 'name', 'phone', 'email', 'limage', 'aimage', 'pincode', 'address')->get()->toArray();
                if ($driver_result) {
                    foreach ($driver_result as $elem) {
                        $driver_data[$elem->id] = $elem;
                    }
                }
            }
            $location_data = DB::table('booking_location_mapping')->whereIn('booking_id', $booking_ids)->select('booking_id', 'start_location_id', 'end_location_id')->get()->toArray();
            $location_payment_data_query = DB::table('booking_payment')->whereIn('booking_id', $booking_ids)->select('booking_id', 'type', 'base_price', 'tax', 'round_off', 'total', 'transaction_id', 'tax_split_1', 'tax_split_2')->get()->toArray();

            if ($location_payment_data_query) {
                foreach ($location_payment_data_query as $elem) {
                    $location_payment_data[$elem->booking_id] = $elem;
                }
            }
            if ($location_data) {
                $start_location_ids = array_column($location_data, 'start_location_id');
                $end_location_ids = array_column($location_data, 'end_location_id');
                $locations_ids = array_merge($start_location_ids, $end_location_ids);
                $locations_datas = DB::table('booking_locations')->whereIn('booking_id', $booking_ids)->select('address1', 'address2', 'address3', 'city', 'state', 'country', 'postal_code', 'lat', 'long', 'landmark', 'location_id', 'booking_id')->get()->toArray();
                if ($locations_datas) {
                    foreach ($locations_datas as $elem) {
                        if (in_array($elem->location_id, $start_location_ids)) {
                            $booking_location_data[$elem->booking_id]['from_locations'] = $elem;
                        }
                        if (in_array($elem->location_id, $end_location_ids)) {
                            $booking_location_data[$elem->booking_id]['to_locations'][] = $elem;
                        }
                    }
                }
            }
            foreach ($booking_data as $elem) {
                $elem->from_locations = isset($booking_location_data[$elem->booking_id]['from_locations']) ? $booking_location_data[$elem->booking_id]['from_locations'] : [];
                $elem->to_locations = isset($booking_location_data[$elem->booking_id]['to_locations']) ? $booking_location_data[$elem->booking_id]['to_locations'] : null;;
                $elem->payment_data = isset($location_payment_data[$elem->booking_id]) ? $location_payment_data[$elem->booking_id] : null;
                $elem->driver_data = isset($driver_data[$elem->driver_id]) ? $driver_data[$elem->driver_id] : [];
                $result[] = $elem;
            }
        }
        return $result;
    }
    public function taxCalculation($price, $taxRate)
    {
        $price = (float)$price;
        $taxRate = (float)$taxRate;
        $tax = $price * $taxRate / 100;
        $total = $price + $tax;
        if ($total == 0) {
            return $total;
        }
        $calculatedTaxRate = (($total - $price) / $price) * 100;
        return $priceExclVAT = (float)round($calculatedTaxRate, 2);
    }
    public function calculation($category, $distance, $pincode)
    {

        $data =  DB::table('price')->where(['category' => $category, 'pincode' => $pincode])->select('amount', 'tax_split_1', 'tax_split_2', 'tax')->first();

        $base_price = isset($data->amount) ? $data->amount : "";
        $tax_split_amount_1 = isset($data->tax_split_1) ? $data->tax_split_1 : "";
        $tax_split_amount_2 = isset($data->tax_split_2) ? $data->tax_split_2 : "";
        $tax = isset($data->tax) ? $data->tax : "";
        $tax = $this->taxCalculation($base_price, $tax);
        $total = (float)$tax + (float)$base_price;
        return ['total' => $total, 'base_price' => $base_price, 'tax_split_1' => $this->taxCalculation($base_price, $tax_split_amount_1), 'tax_split_2' => $this->taxCalculation($base_price, $tax_split_amount_2), 'tax' => $this->taxCalculation($base_price, $tax)];
    }
    /**
     * create new booking
     * */
    public function create($data, $source)
    {
        // DEBUG: Log input data
        Log::info('Booking create input', ['data' => $data, 'source' => $source]);

        $user_id = $data['user_id'] ?? null;
        $company_id = $data['company_id'] ?? null;
        $external_phone = $data['external_phone'] ?? null;
        $category = $data['category'] ?? null;
        $distance = $data['distance'] ?? null;
        $pincode = $data['pincode'] ?? null;
        $from_location = $data['from_location'] ?? null;
        $to_location = $data['to_location'] ?? [];

        // DEBUG: Log extracted variables
        Log::info('Extracted variables', [
            'user_id' => $user_id,
            'company_id' => $company_id,
            'external_phone' => $external_phone,
            'source' => $source
        ]);

        $booking_id = 'doc-' . $this->guidv4();
        $booing_insert_data = [
            "booking_id" => $booking_id,
            "source" => $source,
            "category" => $category,
            "distance" => $distance,
            "pincode" => $pincode
        ];

        if ((string)$source === '0') {
            $booing_insert_data["customer_id"] = $user_id;
            Log::info('User booking data', $booing_insert_data);
        } else {
            $booing_insert_data["company_id"] = $company_id;
            $booing_insert_data["external_phone"] = $external_phone;
            Log::info('Company booking data', $booing_insert_data);
        }

        // DEBUG: Dump before insert
        // dd($booing_insert_data);

        $from_location_id = 'loc-' . time() . '-' . (rand(1000, 9999));
        $to_location_ids = [];
        $update_address_data = [];

        // Process to_locations (array)
        foreach ($to_location as $index => $elem) {
            $to_loc_id = 'loc-' . time() . '-' . rand(1000, 9999);
            $to_location_ids[] = $to_loc_id;
            $update_address_data[] = [
                'location_id' => $to_loc_id,
                'booking_id' => $booking_id,
                'address1' => $elem['address1'] ?? '',
                'address2' => $elem['address2'] ?? '',
                'address3' => $elem['address3'] ?? '',
                'city' => $elem['city'] ?? '',
                'state' => $elem['state'] ?? '',
                'country' => $elem['country'] ?? '',
                'postal_code' => $elem['postal_code'] ?? '',
                'lat' => $elem['lat'] ?? 0,
                'long' => $elem['long'] ?? 0,
                'landmark' => $elem['landmark'] ?? ''
            ];
        }

        // Process from_location (single object)
        $update_address_data[] = [
            'location_id' => $from_location_id,
            'booking_id' => $booking_id,
            'address1' => $from_location['address1'] ?? '',
            'address2' => $from_location['address2'] ?? '',
            'address3' => $from_location['address3'] ?? '',
            'city' => $from_location['city'] ?? '',
            'state' => $from_location['state'] ?? '',
            'country' => $from_location['country'] ?? '',
            'postal_code' => $from_location['postal_code'] ?? '',
            'lat' => $from_location['lat'] ?? 0,
            'long' => $from_location['long'] ?? 0,
            'landmark' => $from_location['landmark'] ?? ''
        ];

        $update_location_mapping = [];
        foreach ($to_location_ids as $to_id) {
            $update_location_mapping[] = [
                "booking_id" => $booking_id,
                "start_location_id" => $from_location_id,
                "end_location_id" => $to_id
            ];
        }

        $price_data = $this->calculation($category, $distance, $pincode);
        Log::info('Price data', $price_data);

        $base_price = (float) ($price_data['base_price'] ?? 0);
        $tax_amount = (float) ($price_data['tax'] ?? 0);
        $tax_split_1 = (float) ($price_data['tax_split_1'] ?? 0);
        $tax_split_2 = (float) ($price_data['tax_split_2'] ?? 0);
        $total = $base_price + $tax_amount;

        $update_payment = [
            "booking_id" => $booking_id,
            "base_price" => $base_price,
            "tax" => $tax_amount,
            "tax_split_1" => $tax_split_1,
            "tax_split_2" => $tax_split_2,
            "total" => $total
        ];

        $update_history_data = [
            "booking_id" => $booking_id,
            "action" => "BOOKING CREATE"
        ];
        if ((string)$source === '0') {
            $update_history_data["user_id"] = $user_id;
        } else {
            $update_history_data["phone"] = $external_phone;
        }

        // Insert all data
        DB::table('booking')->insert($booing_insert_data);
        DB::table('booking_locations')->insert($update_address_data);
        DB::table('booking_payment')->insert($update_payment);
        DB::table('booking_history')->insert($update_history_data);
        DB::table('booking_location_mapping')->insert($update_location_mapping);

        Log::info('Booking created successfully', ['booking_id' => $booking_id]);

        return $booking_id;
    }
}
