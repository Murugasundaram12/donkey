<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class WhatsAppOtpService
{
    public function send(string $phone, string $otp): bool
    {
        return $this->sendTemplate($phone, config('services.whatsapp.otp_template'), [
            [
                'type' => 'body',
                'parameters' => [
                    ['type' => 'text', 'text' => $otp],
                ],
            ],
            [
                'type' => 'button',
                'sub_type' => 'url',
                'index' => '0',
                'parameters' => [
                    ['type' => 'text', 'text' => $otp],
                ],
            ],
        ], 'WhatsApp OTP');
    }

    public function sendBodyTemplate(string $phone, string $templateName, array $variables = []): bool
    {
        $components = [];

        if (!empty($variables)) {
            $components[] = [
                'type' => 'body',
                'parameters' => array_map(
                    fn ($value) => ['type' => 'text', 'text' => (string) $value],
                    $variables
                ),
            ];
        }

        return $this->sendTemplate($phone, $templateName, $components, 'WhatsApp template');
    }

    public function submissionVariables(object $subscriber): array
    {
        $availableVariables = [
            'name' => $subscriber->name,
            'subscriber_id' => $subscriber->subscriberId,
            'mobile' => $subscriber->mobile,
        ];

        return collect(config('services.whatsapp.submission_template_variables', []))
            ->map(fn ($key) => $availableVariables[$key] ?? null)
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->values()
            ->all();
    }

    private function sendTemplate(string $phone, string $templateName, array $components = [], string $logPrefix = 'WhatsApp template'): bool
    {
        $token = config('services.whatsapp.token');
        if (!$token) {
            Log::warning($logPrefix . ' was not sent because WHATSAPP_API_TOKEN is not configured.');
            return false;
        }

        try {
            $template = [
                'language' => [
                    'policy' => 'deterministic',
                    'code' => 'en',
                ],
                'name' => $templateName,
            ];

            if (!empty($components)) {
                $template['components'] = $components;
            }

            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->post(config('services.whatsapp.endpoint') . '?token=' . urlencode($token), [
                    'to' => $this->formatPhone($phone),
                    'type' => 'template',
                    'template' => $template,
                ]);

            if ($response->successful()) {
                return true;
            }

            Log::warning($logPrefix . ' API rejected the request.', [
                'status' => $response->status(),
                'response' => substr($response->body(), 0, 1000),
            ]);
        } catch (\Throwable $exception) {
            Log::warning($logPrefix . ' API request failed.', [
                'message' => $exception->getMessage(),
            ]);
        }

        return false;
    }

    private function formatPhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);
        $countryCode = (string) config('services.whatsapp.country_code', '91');

        if (strlen($digits) === 10) {
            return $countryCode . $digits;
        }

        if (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            return $countryCode . substr($digits, 1);
        }

        if (str_starts_with($digits, $countryCode) && strlen($digits) > strlen($countryCode) + 7) {
            return $digits;
        }

        throw new InvalidArgumentException('The subscriber mobile number is not valid for WhatsApp delivery.');
    }
}
