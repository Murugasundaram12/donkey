<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Subscriber;
use Symfony\Component\HttpFoundation\Response;

class EnsureVendorAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || !($user instanceof Subscriber)) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated vendor access.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (isset($user->blockedstatus) && (int) $user->blockedstatus === 0) {
            return response()->json([
                'status' => false,
                'message' => 'Your vendor account has been blocked. Please contact admin.'
            ], Response::HTTP_FORBIDDEN);
        }

        $isExpired = false;
        if (!empty($user->expiryDate)) {
            try {
                $expiry = \Carbon\Carbon::parse($user->expiryDate)->endOfDay();
                $isExpired = $expiry->isPast();
            } catch (\Throwable $e) {
                $isExpired = (isset($user->status) && (int) $user->status === 0);
            }
        } else {
            $isExpired = (isset($user->status) && (int) $user->status === 0);
        }

        if ($isExpired) {
            return response()->json([
                'status' => false,
                'message' => 'Your vendor account is inactive or expired. Please contact admin.'
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
