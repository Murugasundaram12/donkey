<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $otp;
    protected $userData;

    public function __construct($otp, array $userData = [])
    {
        $this->otp = $otp;
        $this->userData = $userData;
    }

    public function build()
    {
        return $this->subject('Donkey Deliveries verification code')
            ->view('emails.otp', [
                'otp' => $this->otp,
                'userData' => $this->userData,
            ]);
    }
}
