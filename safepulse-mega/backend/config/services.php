<?php

return [

    'mailgun' => [
        'domain'   => env('MAILGUN_DOMAIN'),
        'secret'   => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme'   => 'https',
    ],

    'postmark' => ['token' => env('POSTMARK_TOKEN')],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Mistral AI (🇫🇷 Paris) — SafePulse Layer-2 Scam Detection
    |--------------------------------------------------------------------------
    */
    'mistral' => [
        'key'   => env('MISTRAL_API_KEY'),
        'model' => env('MISTRAL_MODEL', 'mistral-small-latest'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Token — Required for /api/admin/* endpoints
    |--------------------------------------------------------------------------
    | Generate a strong random token and put it in your .env as ADMIN_TOKEN.
    | The frontend /admin page will require this token to authenticate.
    */
    'admin' => [
        'token' => env('ADMIN_TOKEN'),
    ],

];
