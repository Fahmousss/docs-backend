<?php

declare(strict_types=1);

namespace App\Commands\Auth;

use App\DTOs\Auth\LoginData;
use Closure;

final class CreateAuthToken
{
    public function handle(LoginData $payload, Closure $next): mixed
    {
        $payload->token = $payload->user->createToken('auth-token')->plainTextToken;

        return $next($payload);
    }
}
