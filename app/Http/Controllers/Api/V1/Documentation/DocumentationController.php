<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Documentation;

use App\DTOs\Documentation\DocumentationPayload;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\Documentation\UpdateDocumentationRequest;
use App\Http\Resources\Documentation\DocumentationResource;
use App\Processes\Documentation\UpdateDocumentationProcess;
use App\Queries\Documentation\GetProductDocumentation;
use Illuminate\Http\JsonResponse;

final class DocumentationController extends ApiController
{
    public function __construct(
        private readonly UpdateDocumentationProcess $process,
    ) {}

    /**
     * @response array{ success: bool, message: string, data: array{ items: DocumentationResource[] } }
     */
    public function show(string $productId): JsonResponse
    {
        $docs = (new GetProductDocumentation($productId))->execute();

        return $this->success([
            'items' => DocumentationResource::collection($docs)->resolve(),
        ], 'Documentation retrieved successfully');
    }

    public function update(string $productId, UpdateDocumentationRequest $request): JsonResponse
    {
        $payload = DocumentationPayload::from([
            'productId' => $productId,
            'sections'  => $request->validated('sections'),
        ]);

        $this->process->run($payload);

        return $this->success(null, 'Documentation updated successfully');
    }
}
