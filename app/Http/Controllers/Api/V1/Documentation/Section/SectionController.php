<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Documentation\Section;

use App\DTOs\Documentation\Section\CreateSectionData;
use App\DTOs\Documentation\Section\UpdateSectionData;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\Documentation\Section\StoreSectionRequest;
use App\Http\Requests\Api\V1\Documentation\Section\UpdateSectionRequest;
use App\Processes\Documentation\Section\CreateSectionProcess;
use App\Processes\Documentation\Section\DeleteSectionProcess;
use App\Processes\Documentation\Section\UpdateSectionProcess;
use Illuminate\Http\JsonResponse;

final class SectionController extends ApiController
{
    public function __construct(
        private readonly CreateSectionProcess $createProcess,
        private readonly UpdateSectionProcess $updateProcess,
        private readonly DeleteSectionProcess $deleteProcess,
    ) {}

    /**
     * Create documentation section
     */
    public function store(string $productId, StoreSectionRequest $request): JsonResponse
    {
        $payload = CreateSectionData::from([
            ...$request->validated(),
            'productId' => $productId,
        ]);

        $this->createProcess->run($payload);

        return $this->created(null, 'Documentation section created successfully');
    }

    /**
     * Update documentation section
     */
    public function update(string $productId, string $sectionId, UpdateSectionRequest $request): JsonResponse
    {
        $payload = UpdateSectionData::from([
            ...$request->validated(),
            'id' => $sectionId,
        ]);

        $this->updateProcess->run($payload);

        return $this->success(null, 'Documentation section updated successfully');
    }

    /**
     * Delete documentation section
     */
    public function destroy(string $productId, string $sectionId): JsonResponse
    {
        $payload = (object) ['id' => $sectionId];

        $this->deleteProcess->run($payload);

        return $this->noContent();
    }
}
