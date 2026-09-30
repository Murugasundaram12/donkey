<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class FirebaseAccessTokenService
{
    private const REFRESH_BUFFER_SECONDS = 120;

    public function getVendorAccessToken(): string
    {
        $cacheKey = $this->cacheKey();
        $cachedToken = Cache::get($cacheKey);
        if (is_string($cachedToken) && $cachedToken !== '') {
            return $cachedToken;
        }

        $credential = $this->loadVendorCredential();
        $tokenResponse = Http::asForm()
            ->timeout(10)
            ->post($credential['token_uri'], [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $this->createAssertion($credential),
            ]);

        if ($tokenResponse->failed()) {
            $error = $tokenResponse->json();
            $errorCode = is_array($error) && is_string($error['error'] ?? null)
                ? $error['error']
                : 'unknown';
            $errorDescription = is_array($error) && is_string($error['error_description'] ?? null)
                ? $error['error_description']
                : null;

            throw new RuntimeException(
                'Firebase OAuth token request failed with HTTP status '
                . $tokenResponse->status()
                . ' (' . $errorCode . ($errorDescription ? ': ' . $errorDescription : '') . ').'
            );
        }

        $accessToken = $tokenResponse->json('access_token');
        $expiresIn = (int) $tokenResponse->json('expires_in', 3600);

        if (!is_string($accessToken) || $accessToken === '') {
            throw new RuntimeException('Firebase OAuth token response did not contain an access token.');
        }

        Cache::put(
            $cacheKey,
            $accessToken,
            max(60, $expiresIn - self::REFRESH_BUFFER_SECONDS)
        );

        return $accessToken;
    }

    private function cacheKey(): string
    {
        $projectId = trim((string) config('services.firebase.vendor_project_id'));

        return 'firebase.vendor.access_token.' . sha1($projectId);
    }

    /**
     * @return array{project_id:string,client_email:string,private_key:string,token_uri:string}
     */
    private function loadVendorCredential(): array
    {
        $configuredProjectId = trim((string) config('services.firebase.vendor_project_id'));
        $configuredPath = trim((string) config('services.firebase.vendor_credentials'));

        if ($configuredProjectId === '' || $configuredPath === '') {
            throw new RuntimeException('Vendor Firebase project or credentials path is not configured.');
        }

        $path = $this->resolveCredentialPath($configuredPath);
        if (!is_file($path) || !is_readable($path)) {
            throw new RuntimeException('Vendor Firebase credential file is missing or unreadable.');
        }

        $credential = json_decode((string) file_get_contents($path), true);
        if (!is_array($credential)) {
            throw new RuntimeException('Vendor Firebase credential file is not valid JSON.');
        }

        foreach (['project_id', 'client_email', 'private_key', 'token_uri'] as $field) {
            if (!isset($credential[$field]) || !is_string($credential[$field]) || trim($credential[$field]) === '') {
                throw new RuntimeException('Vendor Firebase credential file is missing required metadata.');
            }
        }

        if (!hash_equals($configuredProjectId, trim($credential['project_id']))) {
            throw new RuntimeException('Vendor Firebase credential project does not match the configured project.');
        }

        return [
            'project_id' => trim($credential['project_id']),
            'client_email' => trim($credential['client_email']),
            'private_key' => $credential['private_key'],
            'token_uri' => trim($credential['token_uri']),
        ];
    }

    private function resolveCredentialPath(string $configuredPath): string
    {
        if ($configuredPath[0] === DIRECTORY_SEPARATOR) {
            return $configuredPath;
        }

        // PHP-FPM may expose the application through a jail/symlink path that
        // open_basedir rejects even though the real application path is allowed.
        // Resolve the application root before joining a relative credential path.
        $applicationRoot = realpath(base_path()) ?: base_path();

        return $applicationRoot . DIRECTORY_SEPARATOR . ltrim($configuredPath, DIRECTORY_SEPARATOR);
    }

    /**
     * @param array{project_id:string,client_email:string,private_key:string,token_uri:string} $credential
     */
    private function createAssertion(array $credential): string
    {
        $issuedAt = time();
        $header = $this->base64UrlEncode(json_encode([
            'alg' => 'RS256',
            'typ' => 'JWT',
        ], JSON_THROW_ON_ERROR));
        $claims = $this->base64UrlEncode(json_encode([
            'iss' => $credential['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => $credential['token_uri'],
            'iat' => $issuedAt,
            'exp' => $issuedAt + 3600,
        ], JSON_THROW_ON_ERROR));

        $unsignedToken = $header . '.' . $claims;
        $signature = '';
        if (!openssl_sign($unsignedToken, $signature, $credential['private_key'], OPENSSL_ALGO_SHA256)) {
            throw new RuntimeException('Unable to sign Firebase OAuth assertion.');
        }

        return $unsignedToken . '.' . $this->base64UrlEncode($signature);
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}
