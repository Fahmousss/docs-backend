<?php

declare(strict_types=1);

namespace App\Processes\Auth;

use App\Commands\Auth\RevokeCurrentToken;
use App\Processes\Process;

final class LogoutProcess extends Process
{
    protected array $tasks = [
        RevokeCurrentToken::class,
    ];
}
