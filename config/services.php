<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'firebase' => [
        'user_project_id' => env('FCM_USER_PROJECT_ID', 'donkey-user'),
        'driver_project_id' => env('FCM_DRIVER_PROJECT_ID', 'donkey-driver'),
    ],

    'whatsapp' => [
        'onboarding_otp_enabled' => env('WHATSAPP_ONBOARDING_OTP_ENABLED', false),
        'onboarding_message_enabled' => env('WHATSAPP_ONBOARDING_MESSAGE_ENABLED', false),
        'endpoint' => env('WHATSAPP_API_ENDPOINT', 'https://backend.wacto.ai/v1/message/send-message'),
        'token' => env('WHATSAPP_API_TOKEN', env('WACTO_WHATSAPP_TOKEN')),
        'otp_template' => env('WHATSAPP_OTP_TEMPLATE', 'otp_verification'),
        'submission_template' => env('WHATSAPP_SUBMISSION_TEMPLATE', 'application_recevied'),
        'approval_template' => env('WHATSAPP_APPROVAL_TEMPLATE', 'pbpwelcomeone'),
        'rejection_template' => env('WHATSAPP_REJECTION_TEMPLATE', 'rejected_application'),
        'submission_template_variables' => array_filter(array_map('trim', explode(',', env('WHATSAPP_SUBMISSION_TEMPLATE_VARIABLES', '')))),
        'country_code' => env('WHATSAPP_DEFAULT_COUNTRY_CODE', '91'),
    ],

];
