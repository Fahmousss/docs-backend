<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Processes\Auth\LogoutProcess;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

#[Group('Authentication')]
final class LogoutController extends ApiController
{
    public function __construct(
        private readonly LogoutProcess $logoutProcess,
    ) {}

    /**
     * Logout
     */
    public function __invoke(Request $request): JsonResponse
    {
        $this->logoutProcess->run($request);

        return $this->success(message: 'Logged out successfully');
    }
}
