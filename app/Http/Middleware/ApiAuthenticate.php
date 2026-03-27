<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

final class ApiAuthenticate extends Middleware
{
    protected function unauthenticated($request, array $guards): void
    {
        throw new AuthenticationException(
            'Unauthenticated.',
            $guards
        );
    }
}
