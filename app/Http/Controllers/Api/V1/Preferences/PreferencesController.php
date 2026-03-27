<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Preferences;

use App\DTOs\Preferences\PreferencesPayload;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\Preferences\UpdatePreferencesRequest;
use App\Http\Resources\Preferences\PreferencesResource;
use App\Processes\Preferences\UpdatePreferencesProcess;
use App\Queries\Preferences\GetProductPreferences;
use Illuminate\Http\JsonResponse;

final class PreferencesController extends ApiController
{
    public function __construct(
        private readonly UpdatePreferencesProcess $process,
    ) {}

    /**
     * Get product preferences sections
     *
     * @unauthenticated
     *
     * @response array{ success: bool, message: string, data: array{ items: PreferencesResource[] } }
     */
    public function show(string $productId): JsonResponse
    {
        $preferences = (new GetProductPreferences($productId))->execute();

        return $this->success([
            'name'  => 'Preferences',
            'items' => PreferencesResource::collection($preferences)->resolve(),
        ], 'Preferences retrieved successfully');
    }

    /**
     * Update product preferences sections
     */
    public function update(string $productId, UpdatePreferencesRequest $request): JsonResponse
    {
        $payload = PreferencesPayload::from([
            'productId' => $productId,
            'sections'  => $request->validated('sections'),
        ]);

        $this->process->run($payload);

        return $this->success(null, 'Preferences updated successfully');
    }
}
