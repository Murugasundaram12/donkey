<?php

namespace App\Http\Middleware;

use App\Models\Company;
use App\Models\CompanyApiLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiKeyIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        \Log::info('API KEY FROM HEADER: ' . $request->header('X-API-Key'));

        $company = Company::where('api_key', $request->header('X-API-Key'))->first();

        \Log::info('COMPANY FOUND:', ['company' => $company]);
        $apiKey = $request->header('X-API-Key');
        $idempotencyKey = $request->header('Idempotency-Key');
        $startTime = microtime(true);

        // 1. Validate API key presence
        if (empty($apiKey)) {
            return $this->errorResponse('Unauthorized: X-API-Key header is missing.', 401);
        }

        // 2. Lookup company by API key (must be active)
        $company = Company::where('api_key', $apiKey)
            ->where('status', 'active')
            ->first();

        if (!$company) {
            return $this->errorResponse('Unauthorized: Invalid or inactive API key.', 401);
        }

        // 3. Rate limiting per company (per minute)
        $rateLimitKey = 'company_rate_limit:' . $company->id;
        $requestCount = Cache::increment($rateLimitKey);
        if ($requestCount === 1) {
            Cache::put($rateLimitKey, 1, now()->addMinute());
        }

        if ($requestCount > $company->rate_limit_per_minute) {
            return $this->errorResponse('Rate limit exceeded. Try again in a minute.', 429);
        }

        // 4. Daily/Monthly booking limits (only for booking endpoints)
        if ($this->isBookingEndpoint($request)) {
            $limitCheck = $this->checkBookingLimits($company);
            if ($limitCheck !== true) {
                return $this->errorResponse($limitCheck, 429);
            }
        }

        // 5. Idempotency check (prevent duplicate bookings)
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

        // 6. Inject company context into request
        $request->merge([
            'company' => $company,
            'company_id' => $company->id,
            'is_external' => true,
            'source' => 1,
        ]);

        // 7. Process request
        $response = $next($request);

        // 8. Log API call (async-friendly, non-blocking)
        $this->logApiCall($company, $request, $response, $startTime, $idempotencyKey);

        // 9. Add company headers to response (for debugging)
        $response->headers->set('X-Company-Id', $company->company_id);
        $response->headers->set('X-RateLimit-Remaining', max(0, $company->rate_limit_per_minute - $requestCount));

        return $response;
    }

    /**
     * Check if request is a booking creation endpoint
     */
    private function isBookingEndpoint(Request $request): bool
    {
        return in_array($request->route()?->getName(), [
            'bookingtaxi',
            'bookingCalculation',
        ]) || str_contains($request->path(), 'booking');
    }

    /**
     * Check daily/monthly booking limits
     */
    private function checkBookingLimits(Company $company): true|string
    {
        // Reset monthly counter if needed
        if ($company->booking_limit_reset_at && $company->booking_limit_reset_at->isPast()) {
            $company->current_month_bookings = 0;
            $company->booking_limit_reset_at = now()->addMonth();
            $company->save();
        }

        // Monthly limit
        if ($company->monthly_booking_limit && $company->current_month_bookings >= $company->monthly_booking_limit) {
            return 'Monthly booking limit exceeded.';
        }

        // Daily limit (checked via cache for performance)
        if ($company->daily_booking_limit) {
            $dailyKey = 'company_daily_bookings:' . $company->id . ':' . now()->format('Y-m-d');
            $dailyCount = Cache::get($dailyKey, 0);
            if ($dailyCount >= $company->daily_booking_limit) {
                return 'Daily booking limit exceeded.';
            }
        }

        return true;
    }

    /**
     * Log API call to database
     */
    private function logApiCall(Company $company, Request $request, Response $response, float $startTime, ?string $idempotencyKey): void
    {
        try {
            $responseTime = (int) ((microtime(true) - $startTime) * 1000);

            CompanyApiLog::create([
                'company_id' => $company->id,
                'endpoint' => $request->path(),
                'method' => $request->method(),
                'ip_address' => $request->ip(),
                'idempotency_key' => $idempotencyKey,
                'status_code' => $response->getStatusCode(),
                'response_time_ms' => $responseTime,
                'request_payload' => $this->sanitizePayload($request->all()),
                'response_payload' => $this->sanitizeResponse($response),
                'error_message' => $response->getStatusCode() >= 400 ? json_decode($response->getContent(), true)['message'] ?? null : null,
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Fail silently — logging should never break the API
            \Log::error('Failed to log API call: ' . $e->getMessage());
        }
    }

    /**
     * Sanitize request payload for logging (remove sensitive data)
     */
    private function sanitizePayload(array $payload): array
    {
        $sensitive = ['password', 'otp', 'api_key', 'token', 'credit_card'];
        foreach ($sensitive as $key) {
            if (isset($payload[$key])) {
                $payload[$key] = '***REDACTED***';
            }
        }
        return $payload;
    }

    /**
     * Sanitize response for logging
     */
    private function sanitizeResponse(Response $response): ?array
    {
        $content = $response->getContent();
        if (empty($content)) {
            return null;
        }
        $decoded = json_decode($content, true);
        return is_array($decoded) ? $decoded : ['raw' => substr($content, 0, 1000)];
    }

    /**
     * Standard error response
     */
    private function errorResponse(string $message, int $code): Response
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }
}
