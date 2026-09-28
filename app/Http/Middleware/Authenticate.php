<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // API clients must receive Laravel's unauthenticated response instead
        // of being redirected to a browser login page. This also covers API
        // clients that do not send an Accept: application/json header.
        if ($request->expectsJson() || $request->is('api/*') || $request->routeIs('api.*')) {
            return null;
        }

        // Keep the web redirect working even during deployments where the
        // cached route collection does not contain the named login route.
        return \Illuminate\Support\Facades\Route::has('login')
            ? route('login')
            : url('/login');
    }
}
