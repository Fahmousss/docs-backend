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
use Illuminate\Http\Request;

final class ProductController extends ApiController
{
    public function __construct(
        private readonly CreateProductProcess $createProductProcess,
        private readonly UpdateProductProcess $updateProductProcess,
        private readonly DeleteProductProcess $deleteProcess,
    ) {}

    /**
     * Get all products
     */
    public function index(Request $request): JsonResponse
    {
        $products = (new GetAllProducts(
            pageSize: (int) $request->get('PageSize', 15),
            pageNumber: (int) $request->get('PageNumber', 1),
            searchTerm: (string) $request->get('SearchTerm'),
        ))->execute();

        $payload = [
            'name'  => 'Products',
            'items' => ProductResource::collection($products),
        ];

        return $this->success(
            $payload,
            'Products retrieved successfully'
        );
    }

    /**
     * Get product by id
     */
    public function show(string $id): JsonResponse
    {
        $product = (new GetProductById($id))->execute();

        return $this->success(new ProductResource($product), 'Product retrieved successfully');
    }

    /**
     * Store product
     */
    public function store(
        StoreProductRequest $request,
    ): JsonResponse {
        $payload = ProductData::from($request->validated());

        $result = $this->createProductProcess->run($payload);

        $product = new ProductResource($result->product);

        return $this->created($product, 'Product created successfully');
    }

    /**
     * Update product
     */
    public function update(
        UpdateProductRequest $request,
        string $id,
    ): JsonResponse {
        $payload = ProductData::from([
            ...$request->validated(),
            'id'        => $id,
            'productId' => $id, // Required by ValidateProductExists
        ]);

        $result  = $this->updateProductProcess->run($payload);
        $product = new ProductResource($result->product);

        return $this->success($product, 'Product updated successfully');
    }

    /**
     * Delete product
     */
    public function destroy(string $id): JsonResponse
    {
        $payload = (object) [
            'id'        => $id,
            'productId' => $id, // Required by ValidateProductExists
        ];

        $this->deleteProcess->run($payload);

        return $this->noContent();
    }
}
