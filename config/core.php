<?php

return [

    'auth-service' => [
        'url' => env('AUTH_SERVICE_URL'),
        'validate_token_url' => env('AUTH_SERVICE_URL').'/api/v1/auth/validate',
    ],

];