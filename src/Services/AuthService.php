<?php

namespace Raid\Core\Services;

use App\Models\User;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use function App\Services\auth;
use function App\Services\config;
use function App\Services\data_get;

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
     */
    private function validateToken(string $token): bool
    {
        $response = Http::withToken($token)->get(
            config('services.auth.validate_token_url'),
        );

        if ($response->failed()) {
            return false;
        }

        return (bool) $this->authenticate($response->json('user'));
    }

    private function validateCachedToken(string $token): bool
    {
        $key = 'auth:'.hash('sha256', $token).':user';

        $data = Cache::get($key);

        $user = data_get($data, 'valid')
            ? data_get($data, 'user')
            : null;

        return $user && $this->authenticate($user);
    }

    private function authenticate(array $data): Authenticatable
    {
        auth('api')->login(new User($data));

        return auth('api')->user();
    }
}
