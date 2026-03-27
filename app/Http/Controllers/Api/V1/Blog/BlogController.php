<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Blog;

use App\DTOs\Blog\BlogPayload;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\Blog\UpdateBlogRequest;
use App\Http\Resources\Blog\BlogResource;
use App\Processes\Blog\UpdateBlogProcess;
use App\Queries\Blog\GetProductBlog;
use Illuminate\Http\JsonResponse;

final class BlogController extends ApiController
{
    public function __construct(
        private readonly UpdateBlogProcess $updateBlogProcess,
    ) {}

    /**
     * Get product blog sections
     *
     * @unauthenticated
     *
     * @response array{ success: bool, message: string, data: array{ sections: BlogResource[] } }
     */
    public function show(string $productId): JsonResponse
    {
        $blog = (new GetProductBlog($productId))->execute();

        return $this->success([
            'name'     => 'Blog',
            'sections' => BlogResource::collection($blog)->resolve(),
        ], 'Blog retrieved successfully');
    }

    /**
     * Update product blog sections
     */
    public function update(
        string $productId,
        UpdateBlogRequest $request
    ): JsonResponse {
        $data    = $request->validated();
        $payload = BlogPayload::from(['sections' => $data['sections'], 'productId' => $productId]);

        $this->updateBlogProcess->run($payload);

        return $this->success(null, 'Blog updated successfully');
    }
}
