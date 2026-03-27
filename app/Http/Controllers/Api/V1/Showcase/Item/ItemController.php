<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Showcase\Item;

use App\DTOs\Showcase\Item\CreateItemData;
use App\DTOs\Showcase\Item\UpdateItemData;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\Showcase\Item\StoreItemRequest;
use App\Http\Requests\Api\V1\Showcase\Item\UpdateItemRequest;
use App\Processes\Showcase\Item\CreateItemProcess;
use App\Processes\Showcase\Item\DeleteItemProcess;
use App\Processes\Showcase\Item\UpdateItemProcess;
use Illuminate\Http\JsonResponse;

final class ItemController extends ApiController
{
    public function __construct(
        private readonly CreateItemProcess $createProcess,
        private readonly UpdateItemProcess $updateProcess,
        private readonly DeleteItemProcess $deleteProcess,
    ) {}

    /**
     * Create showcase item
     */
    public function store(string $productId, StoreItemRequest $request): JsonResponse
    {
        $payload = CreateItemData::from([
            ...$request->validated(),
            'productId' => $productId,
        ]);

        $this->createProcess->run($payload);

        return $this->created(null, 'Showcase item created successfully');
    }

    /**
     * Update showcase item
     */
    public function update(string $productId, string $itemId, UpdateItemRequest $request): JsonResponse
    {
        $payload = UpdateItemData::from([
            ...$request->validated(),
            'id' => $itemId,
        ]);

        $this->updateProcess->run($payload);

        return $this->success(null, 'Showcase item updated successfully');
    }

    /**
     * Delete showcase item
     */
    public function destroy(string $productId, string $itemId): JsonResponse
    {
        $payload = (object) ['id' => $itemId];

        $this->deleteProcess->run($payload);

        return $this->noContent();
    }
}
