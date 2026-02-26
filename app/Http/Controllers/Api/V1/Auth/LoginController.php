<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\DTOs\Auth\LoginData;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Processes\Auth\LoginProcess;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;

#[Group('Authentication')]
final class LoginController extends ApiController
{
    public function __construct(
        private readonly LoginProcess $loginProcess,
    ) {}

    /**
     * @unauthenticated
     *
     * @param  LoginRequest  $request  Email and password
     *
     * @throws AuthenticationException
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $payload = $this->loginProcess->run(LoginData::from($request));

        return $this->success([
            'user'  => new UserResource($payload->user),
            'token' => $payload->token,
        ], 'Login successful');
    }
}
