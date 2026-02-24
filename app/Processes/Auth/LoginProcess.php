<?php

declare(strict_types=1);

namespace App\Processes\Auth;

use App\Commands\Auth\CreateAuthToken;
use App\Commands\Auth\ValidateCredentials;
use App\Processes\Process;

final class LoginProcess extends Process
{
    protected array $tasks = [
        ValidateCredentials::class,
        CreateAuthToken::class,
    ];
}
