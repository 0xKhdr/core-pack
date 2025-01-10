<?php

namespace Raid\Core\Middleware;

use Closure;
use Illuminate\Http\Request;
use Raid\Core\Services\AuthService;
use Symfony\Component\HttpFoundation\Response;

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
