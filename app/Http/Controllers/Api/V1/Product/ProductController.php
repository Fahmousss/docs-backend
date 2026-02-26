<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Product;

use App\DTOs\Product\ProductData;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\Product\StoreProductRequest;
use App\Http\Requests\Api\V1\Product\UpdateProductRequest;
use App\Http\Resources\Product\ProductResource;
use App\Processes\Product\CreateProductProcess;
use App\Processes\Product\DeleteProductProcess;
use App\Processes\Product\UpdateProductProcess;
use App\Queries\Product\GetAllProducts;
use App\Queries\Product\GetProductById;
use Illuminate\Http\JsonResponse;

final class ProductController extends ApiController
{
    public function __construct(
        private readonly CreateProductProcess $createProductProcess,
        private readonly UpdateProductProcess $updateProductProcess,
        private readonly DeleteProductProcess $deleteProcess,
        private readonly GetAllProducts $getAllProducts,
    ) {}

    public function index(): JsonResponse
    {
        return $this->success(ProductResource::collection($this->getAllProducts->execute()), 'Products retrieved successfully');
    }

    public function show(string $id): JsonResponse
    {
        return $this->success(new ProductResource((new GetProductById($id))->execute()), 'Product retrieved successfully');
    }

    public function store(
        StoreProductRequest $request,
    ): JsonResponse {
        $payload = ProductData::from($request->validated());

        $result = $this->createProductProcess->run($payload);

        return $this->created(new ProductResource($result->product), 'Product created successfully');
    }

    public function update(
        UpdateProductRequest $request,
        string $id,
    ): JsonResponse {
        $payload = ProductData::from([
            ...$request->validated(),
            'id'         => $id,
            'product_id' => $id, // Required by ValidateProductExists
        ]);

        $result = $this->updateProductProcess->run($payload);

        return $this->success(new ProductResource($result->product), 'Product updated successfully');
    }

    public function destroy(string $id): JsonResponse
    {
        $payload = (object) [
            'id'         => $id,
            'product_id' => $id, // Required by ValidateProductExists
        ];

        $this->deleteProcess->run($payload);

        return $this->noContent();
    }
}
