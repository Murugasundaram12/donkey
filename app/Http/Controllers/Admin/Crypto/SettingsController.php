<?php

namespace App\Http\Controllers\Admin\Crypto;

use App\Http\Controllers\Controller;
use App\Models\site as Site;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function edit(Site $site)
    {
        return view('admin.crypto.settings.setting', ['site' => $site]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Site $site)
    {
        // dd($request->all(), $site);
        $validator = $request->validate([
            'mining_coin' => 'required|min:1|max:1000',
            'indirect_percentage' => 'required|min:1|max:99',
        ]);

        $site->update($validator);
        $site->save();
        return back()->with('success', "Crypto settings Updated!!!");
    }

    public function addCoins(Request $request, Site $site)
    {
        // dd($request->all(), $site);
        $validator = $request->validate([
            'add_coin' => 'required|min:1|max:1000000000'
        ]);

        // dd($validator);
        $site->mining_wallet += $validator['add_coin'];
        $site->save();
        return back()->with('success', "Coins Added Successfully!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Site $site)
    {
        //
    }
}

