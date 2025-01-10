<?php

return [

    'auth' => [
        'url' => env('AUTH_SERVICE_URL'),
        'validate_token_url' => env('AUTH_SERVICE_VALIDATE_TOKEN_URL'),
        'defaults' => [
            'model' => env('AUTH_USER_MODEL', App\Models\User::class),
            'guard' => env('AUTH_GUARD', 'api'),
        ],
    ],

];
