<?php

declare(strict_types=1);

namespace App\DTOs\Auth;

use App\Models\User;
use Spatie\LaravelData\Data;

final class LoginData extends Data
{
    public function __construct(
        public string $email,
        public string $password,
        public ?User $user = null,
        public ?string $token = null,
    ) {}
}
