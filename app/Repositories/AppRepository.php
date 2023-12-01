<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Cache;

class AppRepository
{
    public function sendEmailVerificationCode($email)
    {
        $verificationCode = generateCode();
        $emailText = $verificationCode . " is your verification code for Laravel Sancyum Application Login";
        $emailSendArray = array(
            'to' => $email,
            'verification_code' => $emailText,
            'subject' => 'Verification Code for User Login',
        );
        send_email('mail', $emailSendArray);
        Cache::put($email, $verificationCode, 10 * 60);
    }
}
