<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class WhatsAppOtpService
{
    public function send(string $phone, string $otp): bool
    {
        $token = config('services.whatsapp.token');
        if (!$token) {
            Log::warning('WhatsApp OTP was not sent because WHATSAPP_API_TOKEN is not configured.');
            return false;
        }

        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->post(config('services.whatsapp.endpoint') . '?token=' . urlencode($token), [
                    'to' => $this->formatPhone($phone),
                    'type' => 'template',
                    'template' => [
                        'language' => [
                            'policy' => 'deterministic',
                            'code' => 'en',
                        ],
                        'name' => config('services.whatsapp.otp_template'),
                        'components' => [
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
                        ],
                    ],
                ]);

            if ($response->successful()) {
                return true;
            }

            Log::warning('WhatsApp OTP API rejected the request.', [
                'status' => $response->status(),
                'response' => substr($response->body(), 0, 1000),
            ]);
        } catch (\Throwable $exception) {
            Log::warning('WhatsApp OTP API request failed.', [
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
