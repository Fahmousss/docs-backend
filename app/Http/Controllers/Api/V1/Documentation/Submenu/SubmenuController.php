<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Documentation\Submenu;

use App\DTOs\Documentation\Submenu\CreateSubmenuData;
use App\DTOs\Documentation\Submenu\UpdateSubmenuData;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\Documentation\Submenu\StoreSubmenuRequest;
use App\Http\Requests\Api\V1\Documentation\Submenu\UpdateSubmenuRequest;
use App\Processes\Documentation\Submenu\CreateSubmenuProcess;
use App\Processes\Documentation\Submenu\DeleteSubmenuProcess;
use App\Processes\Documentation\Submenu\UpdateSubmenuProcess;
use Illuminate\Http\JsonResponse;

final class SubmenuController extends ApiController
{
    public function __construct(
        private readonly CreateSubmenuProcess $createProcess,
        private readonly UpdateSubmenuProcess $updateProcess,
        private readonly DeleteSubmenuProcess $deleteProcess,
    ) {}

    /**
     * Create documentation submenu
     */
    public function store(
        string $productId,
        string $sectionId,
        string $menuId,
        StoreSubmenuRequest $request
    ): JsonResponse {
        $payload = CreateSubmenuData::from([
            ...$request->validated(),
            'menuId' => $menuId,
        ]);

        $this->createProcess->run($payload);

        return $this->created(null, 'Documentation submenu created successfully');
    }

    /**
     * Update documentation submenu
     */
    public function update(
        string $productId,
        string $sectionId,
        string $menuId,
        string $submenuId,
        UpdateSubmenuRequest $request
    ): JsonResponse {
        $payload = UpdateSubmenuData::from([
            ...$request->validated(),
            'id' => $submenuId,
        ]);

        $this->updateProcess->run($payload);

        return $this->success(null, 'Documentation submenu updated successfully');
    }

    /**
     * Delete documentation submenu
     */
    public function destroy(
        string $productId,
        string $sectionId,
        string $menuId,
        string $submenuId
    ): JsonResponse {
        $payload = (object) ['id' => $submenuId];

        $this->deleteProcess->run($payload);

        return $this->noContent();
    }
}
