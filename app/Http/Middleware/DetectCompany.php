<?php

namespace App\Http\Middleware;

use App\Models\Company;
use App\Models\CompanyApiLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class DetectCompany
{
    /**
     * Handle an incoming request.
     * This middleware is OPTIONAL — if X-API-Key is present, it validates and injects company.
     * If X-API-Key is missing, it does nothing (request proceeds as internal booking).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');
        $idempotencyKey = $request->header('Idempotency-Key');
        $startTime = microtime(true);

        // No API key → internal booking, skip all external logic
        if (empty($apiKey)) {
            $request->merge([
                'is_external' => false,
                'source' => 0,
                'company_id' => null,
            ]);
            return $next($request);
        }

        // API key present → validate and treat as external booking
        $company = Company::where('api_key', $apiKey)
            ->where('status', 'active')
            ->first();

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Invalid or inactive API key.'
            ], 401);
        }

        // Rate limiting per company
        $rateLimitKey = 'company_rate_limit:' . $company->id;
        $requestCount = Cache::increment($rateLimitKey);
        if ($requestCount === 1) {
            Cache::put($rateLimitKey, 1, now()->addMinute());
        }

        if ($requestCount > $company->rate_limit_per_minute) {
            return response()->json([
                'success' => false,
                'message' => 'Rate limit exceeded. Try again in a minute.'
            ], 429);
        }

        // Booking limits check (only for booking endpoints)
        if ($this->isBookingEndpoint($request)) {
            $limitCheck = $this->checkBookingLimits($company);
            if ($limitCheck !== true) {
                return response()->json([
                    'success' => false,
                    'message' => $limitCheck
                ], 429);
            }
        }

        // Idempotency check
        if ($idempotencyKey && $this->isBookingEndpoint($request)) {
            $existing = CompanyApiLog::where('company_id', $company->id)
                ->where('idempotency_key', $idempotencyKey)
                ->where('status_code', 200)
                ->first();

            if ($existing) {
                return response()->json(
                    $existing->response_payload ?? ['success' => true, 'message' => 'Duplicate request'],
                    200
                );
            }
        }

        // Inject company context
        $request->merge([
            'company' => $company,
            'company_id' => $company->id,
            'is_external' => true,
            'source' => 1,
        ]);

        // Process request
        $response = $next($request);

        // Log API call
        $this->logApiCall($company, $request, $response, $startTime, $idempotencyKey);

        // Add response headers
        $response->headers->set('X-Company-Id', $company->company_id);
        $response->headers->set('X-RateLimit-Remaining', max(0, $company->rate_limit_per_minute - $requestCount));

        return $response;
    }

    private function isBookingEndpoint(Request $request): bool
    {
        $path = $request->path();
        return str_contains($path, 'bookingtaxi') || str_contains($path, 'booking/calculation');
    }

    private function checkBookingLimits(Company $company): true|string
    {
        if ($company->booking_limit_reset_at && $company->booking_limit_reset_at->isPast()) {
            $company->current_month_bookings = 0;
            $company->booking_limit_reset_at = now()->addMonth();
            $company->save();
        }

        if ($company->monthly_booking_limit && $company->current_month_bookings >= $company->monthly_booking_limit) {
            return 'Monthly booking limit exceeded.';
        }

        if ($company->daily_booking_limit) {
            $dailyKey = 'company_daily_bookings:' . $company->id . ':' . now()->format('Y-m-d');
            $dailyCount = Cache::get($dailyKey, 0);
            if ($dailyCount >= $company->daily_booking_limit) {
                return 'Daily booking limit exceeded.';
            }
        }

        return true;
    }

    private function logApiCall(Company $company, Request $request, Response $response, float $startTime, ?string $idempotencyKey): void
    {
        try {
            $responseTime = (int) ((microtime(true) - $startTime) * 1000);
            $payload = $request->all();
            foreach (['password', 'otp', 'api_key', 'token'] as $key) {
                if (isset($payload[$key])) $payload[$key] = '***REDACTED***';
            }

            CompanyApiLog::create([
                'company_id' => $company->id,
                'endpoint' => $request->path(),
                'method' => $request->method(),
                'ip_address' => $request->ip(),
                'idempotency_key' => $idempotencyKey,
                'status_code' => $response->getStatusCode(),
                'response_time_ms' => $responseTime,
                'request_payload' => $payload,
                'response_payload' => json_decode($response->getContent(), true),
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            \Log::error('Failed to log API call: ' . $e->getMessage());
        }
    }
}
