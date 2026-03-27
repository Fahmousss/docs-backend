<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Preferences\Section;

use App\DTOs\Preferences\Section\CreateSectionData;
use App\DTOs\Preferences\Section\UpdateSectionData;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\Preferences\Section\StoreSectionRequest;
use App\Http\Requests\Api\V1\Preferences\Section\UpdateSectionRequest;
use App\Processes\Preferences\Section\CreateSectionProcess;
use App\Processes\Preferences\Section\DeleteSectionProcess;
use App\Processes\Preferences\Section\UpdateSectionProcess;
use Illuminate\Http\JsonResponse;

final class SectionController extends ApiController
{
    public function __construct(
        private readonly CreateSectionProcess $createProcess,
        private readonly UpdateSectionProcess $updateProcess,
        private readonly DeleteSectionProcess $deleteProcess,
    ) {}

    /**
     * Create preference section
     */
    public function store(string $productId, StoreSectionRequest $request): JsonResponse
    {
        $payload = CreateSectionData::from([
            ...$request->validated(),
            'productId' => $productId,
        ]);

        $this->createProcess->run($payload);

        return $this->created(null, 'Preference section created successfully');
    }

    /**
     * Update preference section
     */
    public function update(string $productId, string $sectionId, UpdateSectionRequest $request): JsonResponse
    {
        $payload = UpdateSectionData::from([
            ...$request->validated(),
            'id' => $sectionId,
        ]);

        $this->updateProcess->run($payload);

        return $this->success(null, 'Preference section updated successfully');
    }

    /**
     * Delete preference section
     */
    public function destroy(string $productId, string $sectionId): JsonResponse
    {
        $payload = (object) ['id' => $sectionId];

        $this->deleteProcess->run($payload);

        return $this->noContent();
    }
}
