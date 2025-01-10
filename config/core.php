<?php

return [

    'auth' => [
        'url' => env('AUTH_SERVICE_URL'),
        'validate_token_url' => env('AUTH_SERVICE_URL').'/api/v1/auth/validate',
        'defaults' => [
            'model' => env('AUTH_USER_MODEL', App\Models\User::class),
            'guard' => env('AUTH_GUARD', 'api'),
        ],
    ],

];
