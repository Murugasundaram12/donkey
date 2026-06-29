<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class CacheController extends Controller
{
    public function clear()
    {
        Artisan::call('route:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');

        return 'Cache rebuilt!';
    }
}
