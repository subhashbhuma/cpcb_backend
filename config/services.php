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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

  'sms' => [
    'endpoint' => env('SMS_DLT_ENDPOINT', 'https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT'),
    'username'      => env('SMS_USERNAME'),
    'password'      => env('SMS_PASSWORD'),
    'sender_id'     => env('SMS_SENDER_ID'),  
    'entity_id'     => env('SMS_DLT_ENTITY_ID'),
    'telemarketer_id'=> env('SMS_DLT_TELEMARKETER_ID'),
    'secure_key'    => env('SMS_SECURE_KEY'),
    'header'        => env('SMS_HEADER'),
    'timeout' => env('SMS_TIMEOUT', 15),
    'whitelisted_ip'   => env('SMS_WHITELISTED_IP'),
    'whitelisted_port' => env('SMS_WHITELISTED_PORT'),
    'templates' => [
        'otp' => [
            'id'   => env('SMS_TEMPLATE_ID'),
            'text' =>env('SMS_TEMPLATE'),
        ],
    ],
  ],
];
