<?php

namespace App\Http\Controllers\Subscriber;

use App\Http\Controllers\Controller;
use App\Models\Pincode;
use App\Models\Pincodebasedcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PincodebasedcategoryController extends Controller
{
    public function  __constract()
    {
        $this->middleware('auth:subscriber')->only(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(Auth::guard('subscriber')->id());
        $pincodes = Pincode::where('usedBy', Auth::guard('subscriber')->id())
            ->get();
        // dd($pincodes);
        return view('subscriber.pincode.index', ['pincodes' => $pincodes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pincode  $pincode
     * @return \Illuminate\Http\Response
     */
    public function show(Pincode $pincode)
    {
        $pincodeBasedCategories = Pincodebasedcategory::where('pincode_id', $pincode->id)
            ->with('category', 'pincode')
            ->get();
        // dd($pincodeBasedCategories);
        return view('subscriber.pincode.show', ['pincodeBasedcategories' => $pincodeBasedCategories]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pincode  $pincode
     * @return \Illuminate\Http\Response
     */
    public function edit(Pincode $pincode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pincode  $pincode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pincodebasedcategory $pincode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pincode  $pincode
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pincode $pincode)
    {
        //
    }

    public function pincodebasedcategorystatus(Request $request)
    {
        //dd($request);
        $pincodebasedcategory = Pincodebasedcategory::where('id', $request->id)->first();
        //dd($pincodebasedcategory);
        if (isset($pincodebasedcategory)) {
            $pincodebasedcategory->update([
                'status' => $request->status == 1 ? 1 : 0
            ]);
            return response()->json([
                'success' => true
            ]);
        }
        return response()->json([
            'success' => false
        ]);
    }
}
