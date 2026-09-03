<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Subscriber;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class EnsureVendorPaymentValid
{
    /**
     * Handle an incoming request.
     *
     * Checks if the authenticated vendor's payment/subscription is valid.
     * Protected vendor business endpoints rely on this middleware.
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

        // 1. Check blocked status
        if (isset($user->blockedstatus) && (int) $user->blockedstatus === 0) {
            return response()->json([
                'status' => false,
                'message' => 'Your vendor account has been blocked. Please contact admin.'
            ], Response::HTTP_FORBIDDEN);
        }

        // 2. Check subscription / payment expiration
        if (!Subscriber::isSubscriberActive($user)) {
            return response()->json([
                'status' => false,
                'message' => 'Your subscription/payment has expired. Please renew your payment.'
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
