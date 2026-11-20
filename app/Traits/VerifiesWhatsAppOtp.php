<?php

namespace App\Traits;

trait VerifiesWhatsAppOtp
{
    /**
     * Determine whether the given phone / mobile number has an active, valid WhatsApp OTP verification session.
     *
     * @param string|null $mobile
     * @return bool
     */
    protected function hasVerifiedOtp(?string $mobile): bool
    {
        $verified = session('subscriber_onboarding_otp_verified');
        if (!$verified || empty($verified['mobile']) || empty($verified['verified_at'])) {
            return false;
        }

        // Verified within 10 minutes (600 seconds)
        if ((now()->timestamp - (int) $verified['verified_at']) > 600) {
            session()->forget('subscriber_onboarding_otp_verified');
            return false;
        }

        $normalisedInput = preg_replace('/\D+/', '', (string) $mobile);

        return $verified['mobile'] === $normalisedInput;
    }
}
