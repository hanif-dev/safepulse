<?php

/*
|--------------------------------------------------------------------------
| SafePulse CORS Configuration
|--------------------------------------------------------------------------
| FRONTEND_URL should be set in .env.
| In Codespaces: http://localhost:5173
| In production: https://your-app.amplifyapp.com
*/

return [
    'paths'                    => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods'          => ['*'],
    'allowed_origins'          => [env('FRONTEND_URL', 'http://localhost:5173')],
    'allowed_origins_patterns' => [
        // Allow any Codespaces forwarded preview URL
        '#^https://.*\.app\.github\.dev$#',
        // Allow any Amplify domain
        '#^https://.*\.amplifyapp\.com$#',
    ],
    'allowed_headers'          => ['*'],
    'exposed_headers'          => [],
    'max_age'                  => 0,
    'supports_credentials'     => false,
];
