<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Documentation\Menu;

use App\DTOs\Documentation\Menu\CreateMenuData;
use App\DTOs\Documentation\Menu\UpdateMenuData;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\Documentation\Menu\StoreMenuRequest;
use App\Http\Requests\Api\V1\Documentation\Menu\UpdateMenuRequest;
use App\Processes\Documentation\Menu\CreateMenuProcess;
use App\Processes\Documentation\Menu\DeleteMenuProcess;
use App\Processes\Documentation\Menu\UpdateMenuProcess;
use Illuminate\Http\JsonResponse;

final class MenuController extends ApiController
{
    public function __construct(
        private readonly CreateMenuProcess $createProcess,
        private readonly UpdateMenuProcess $updateProcess,
        private readonly DeleteMenuProcess $deleteProcess,
    ) {}

    /**
     * Create documentation menu
     */
    public function store(string $productId, string $sectionId, StoreMenuRequest $request): JsonResponse
    {
        $payload = CreateMenuData::from([
            ...$request->validated(),
            'sectionId' => $sectionId,
        ]);

        $this->createProcess->run($payload);

        return $this->created(null, 'Documentation menu created successfully');
    }

    /**
     * Update documentation menu
     */
    public function update(
        string $productId,
        string $sectionId,
        string $menuId,
        UpdateMenuRequest $request
    ): JsonResponse {
        $payload = UpdateMenuData::from([
            ...$request->validated(),
            'id' => $menuId,
        ]);

        $this->updateProcess->run($payload);

        return $this->success(null, 'Documentation menu updated successfully');
    }

    /**
     * Delete documentation menu
     */
    public function destroy(string $productId, string $sectionId, string $menuId): JsonResponse
    {
        $payload = (object) ['id' => $menuId];

        $this->deleteProcess->run($payload);

        return $this->noContent();
    }
}
