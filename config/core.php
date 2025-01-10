<?php

return [

    'auth' => [
        'model' => env('AUTH_USER_MODEL', App\Models\User::class),
        'url' => env('AUTH_SERVICE_URL'),
        'validate_token_url' => env('AUTH_SERVICE_URL').'/api/v1/auth/validate',
    ],

];
