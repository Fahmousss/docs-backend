<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Blog\Section;

use App\DTOs\Blog\Section\CreateSectionData;
use App\DTOs\Blog\Section\UpdateSectionData;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\Blog\Section\StoreSectionRequest;
use App\Http\Requests\Api\V1\Blog\Section\UpdateSectionRequest;
use App\Processes\Blog\Section\CreateSectionProcess;
use App\Processes\Blog\Section\DeleteSectionProcess;
use App\Processes\Blog\Section\UpdateSectionProcess;
use Illuminate\Http\JsonResponse;

final class SectionController extends ApiController
{
    public function __construct(
        private readonly CreateSectionProcess $createProcess,
        private readonly UpdateSectionProcess $updateProcess,
        private readonly DeleteSectionProcess $deleteProcess,
    ) {}

    /**
     * Create blog section
     */
    public function store(string $productId, StoreSectionRequest $request): JsonResponse
    {
        $payload = CreateSectionData::from([
            ...$request->validated(),
            'productId' => $productId,
        ]);

        $this->createProcess->run($payload);

        return $this->created(null, 'Blog section created successfully');
    }

    /**
     * Update blog section
     */
    public function update(string $productId, string $sectionId, UpdateSectionRequest $request): JsonResponse
    {
        $payload = UpdateSectionData::from([
            ...$request->validated(),
            'id' => $sectionId,
        ]);

        $this->updateProcess->run($payload);

        return $this->success(null, 'Blog section updated successfully');
    }

    /**
     * Delete blog section
     */
    public function destroy(string $productId, string $sectionId): JsonResponse
    {
        $payload = (object) ['id' => $sectionId];

        $this->deleteProcess->run($payload);

        return $this->noContent();
    }
}
