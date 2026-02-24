<?php

declare(strict_types=1);

namespace App\Commands\Auth;

use Closure;
use Illuminate\Http\Request;

final class RevokeCurrentToken
{
    public function handle(Request $payload, Closure $next): mixed
    {
        $payload->user()->currentAccessToken()->delete();

        return $next($payload);
    }
}
