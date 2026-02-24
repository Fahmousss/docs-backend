<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Product;

use App\DTOs\Product\ProductData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Product\StoreProductRequest;
use App\Http\Requests\Api\V1\Product\UpdateProductRequest;
use App\Http\Resources\Product\ProductResource;
use App\Processes\Product\CreateProductProcess;
use App\Processes\Product\DeleteProductProcess;
use App\Processes\Product\UpdateProductProcess;
use App\Queries\Product\GetAllProducts;
use App\Queries\Product\GetProductById;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

final class ProductController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly CreateProductProcess $createProductProcess,
        private readonly UpdateProductProcess $updateProductProcess,
        private readonly DeleteProductProcess $deleteProcess,
    ) {}

    public function index(GetAllProducts $query): JsonResponse
    {
        return $this->success(ProductResource::collection($query->get()));
    }

    public function show(int $id): JsonResponse
    {
        $query = new GetProductById($id);

        return $this->success(new ProductResource($query->get()));
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
        int $id,
    ): JsonResponse {
        $payload = ProductData::from([
            ...$request->validated(),
            'id' => $id,
            'product_id' => $id, // Required by ValidateProductExists
        ]);

        $result = $this->updateProductProcess->run($payload);

        return $this->success(new ProductResource($result->product), 'Product updated successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $payload = (object) [
            'id' => $id,
            'product_id' => $id, // Required by ValidateProductExists
        ];

        $this->deleteProcess->run($payload);

        return $this->noContent();
    }
}
