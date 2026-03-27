<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Documentation;

use App\DTOs\Documentation\DocumentationPayload;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\Documentation\UpdateDocumentationRequest;
use App\Http\Resources\Documentation\DocumentationResource;
use App\Http\Resources\Documentation\ProductDocumentationResource;
use App\Processes\Documentation\UpdateDocumentationProcess;
use App\Queries\Documentation\GetAllProductDocumentation;
use App\Queries\Documentation\GetProductDocumentation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class DocumentationController extends ApiController
{
    public function __construct(
        private readonly UpdateDocumentationProcess $process,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $docs = (new GetAllProductDocumentation(
            pageSize: (int) $request->get('PageSize', 15),
            pageNumber: (int) $request->get('PageNumber', 1),
            searchTerm: (string) $request->get('SearchTerm'),
        ))->execute();

        $payload = [
            'name'  => 'Documentation',
            'items' => ProductDocumentationResource::collection($docs),
        ];

        return $this->success(
            $payload,
            'Products retrieved successfully'
        );
    }

    /**
     * Get product documentation sections
     *
     * @unauthenticated
     *
     * @response array{ success: bool, message: string, data: array{ items: DocumentationResource[] } }
     */
    public function show(string $productId): JsonResponse
    {
        $docs = (new GetProductDocumentation($productId))->execute();

        return $this->success([
            'name'  => 'Documentation',
            'items' => DocumentationResource::collection($docs)->resolve(),
        ], 'Documentation retrieved successfully');
    }

    /**
     * Update product documentation sections
     */
    public function update(string $productId, UpdateDocumentationRequest $request): JsonResponse
    {
        $payload = DocumentationPayload::from([
            'productId' => $productId,
            'sections'  => $request->validated('sections'),
        ]);

        $this->process->run($payload);

        return $this->success($payload->sections, 'Documentation updated successfully');
    }
}
