<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Showcase;

use App\DTOs\Showcase\ShowcasePayload;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\Showcase\UpdateShowcaseRequest;
use App\Http\Resources\Showcase\ShowcaseResource;
use App\Processes\Showcase\UpdateShowcaseProcess;
use App\Queries\Showcase\GetProductShowcase;
use Illuminate\Http\JsonResponse;

final class ShowcaseController extends ApiController
{
    public function __construct(
        private readonly UpdateShowcaseProcess $process,
    ) {}

    /**
     * Get product showcase sections
     *
     * @response array{ success: bool, message: string, data: array{ items: ShowcaseResource[] } }
     */
    public function show(string $productId): JsonResponse
    {
        $showcaseItems = (new GetProductShowcase($productId))->execute();

        return $this->success([
            'items' => ShowcaseResource::collection($showcaseItems)->resolve(),
        ], 'Showcase retrieved successfully');
    }

    /**
     * Update product showcase sections
     */
    public function update(string $productId, UpdateShowcaseRequest $request): JsonResponse
    {
        $payload = ShowcasePayload::from([
            'productId' => $productId,
            'items'     => $request->validated('items'),
        ]);

        $this->process->run($payload);

        return $this->success(null, 'Showcase updated successfully');
    }
}
