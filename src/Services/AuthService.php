<?php

namespace Raid\Core\Services;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class AuthService
{
    public function isAuthenticated(string $token): bool
    {
        try {
            return $this->validateCachedToken($token) || $this->validateToken($token);
        } catch (Exception) {
            return false;
        }
    }

    /**
     * @throws ConnectionException
     * @throws Exception
     */
    private function validateToken(string $token): bool
    {
        $response = Http::withToken($token)->get(
            config('core.auth.validate_token_url'),
        );

        if ($response->failed()) {
            return false;
        }

        return (bool) $this->authenticate($response->json('user'));
    }

    /**
     * @throws Exception
     */
    private function validateCachedToken(string $token): bool
    {
        $key = 'auth:'.hash('sha256', $token).':token';

        $data = Cache::get($key);

        $user = data_get($data, 'valid')
            ? data_get($data, 'user')
            : null;

        return $user && $this->authenticate($user);
    }

    /**
     * @throws Exception
     */
    private function authenticate(array $data, ?string $guard = null): Authenticatable
    {
        $model = config('core.auth.defaults.model');

        $authGuard = $guard ?: config('core.auth.defaults.guard');

        if (
            ! $model ||
            ! $authGuard ||
            ! class_exists($model)
        ) {
            throw new Exception('No auth model defined in config/core.php');
        }

        auth($authGuard)->login(new $model($data));

        return auth($guard)->user();
    }
}
