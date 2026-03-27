<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Preferences\Item;

use App\DTOs\Preferences\Item\CreateItemData;
use App\DTOs\Preferences\Item\UpdateItemData;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\Preferences\Item\StoreItemRequest;
use App\Http\Requests\Api\V1\Preferences\Item\UpdateItemRequest;
use App\Processes\Preferences\Item\CreateItemProcess;
use App\Processes\Preferences\Item\DeleteItemProcess;
use App\Processes\Preferences\Item\UpdateItemProcess;
use Illuminate\Http\JsonResponse;

final class ItemController extends ApiController
{
    public function __construct(
        private readonly CreateItemProcess $createProcess,
        private readonly UpdateItemProcess $updateProcess,
        private readonly DeleteItemProcess $deleteProcess,
    ) {}

    /**
     * Create preference item
     */
    public function store(string $productId, string $sectionId, StoreItemRequest $request): JsonResponse
    {
        $payload = CreateItemData::from([
            ...$request->validated(),
            'sectionId' => $sectionId,
        ]);

        $this->createProcess->run($payload);

        return $this->created(null, 'Preference item created successfully');
    }

    /**
     * Update preference item
     */
    public function update(
        string $productId,
        string $sectionId,
        string $itemId,
        UpdateItemRequest $request
    ): JsonResponse {
        $payload = UpdateItemData::from([
            ...$request->validated(),
            'id' => $itemId,
        ]);

        $this->updateProcess->run($payload);

        return $this->success(null, 'Preference item updated successfully');
    }

    /**
     * Delete preference item
     */
    public function destroy(string $productId, string $sectionId, string $itemId): JsonResponse
    {
        $payload = (object) ['id' => $itemId];

        $this->deleteProcess->run($payload);

        return $this->noContent();
    }
}
