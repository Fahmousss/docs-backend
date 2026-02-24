<?php

declare(strict_types=1);

namespace App\Commands\Auth;

use App\DTOs\Auth\LoginData;
use App\Models\User;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

final class ValidateCredentials
{
    public function handle(LoginData $payload, Closure $next): mixed
    {
        $user = User::query()->where('email', $payload->email)->first();

        if (! $user || ! Hash::check($payload->password, $user->password)) {
            throw new AuthenticationException(__('auth.failed'));
        }

        $payload->user = $user;

        return $next($payload);
    }
}
