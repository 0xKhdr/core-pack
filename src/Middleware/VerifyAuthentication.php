<?php

namespace Raid\Core\Middleware;

use Raid\Core\Services\AuthService;
use Closure;
use Illuminate\Http\Request;

readonly class VerifyAuthentication
{
    public function __construct(
        private AuthService $authService,
    ) {}

    public function handle(Request $request, Closure $next): mixed
    {
        $token = $request->bearerToken();

        if (! $token || ! $this->authService->isAuthenticated($token)) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
