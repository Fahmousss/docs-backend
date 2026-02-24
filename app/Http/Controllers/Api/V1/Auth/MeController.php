<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\UserResource;
use App\Queries\Auth\GetCurrentUser;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

#[Group('Authentication')]
final class MeController extends ApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = (new GetCurrentUser($request))->get();

        return $this->success(new UserResource($user));
    }
}
