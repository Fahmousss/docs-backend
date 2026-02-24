<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Product;

use App\DTOs\Product\ProductData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
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
    ) {}

    public function index(GetAllProducts $query): JsonResponse
    {
        return $this->success($query->get());
    }

    public function show(int $id): JsonResponse
    {
        $query = new GetProductById($id);

        return $this->success($query->get());
    }

    public function store(
        StoreProductRequest $request,
    ): JsonResponse {
        $payload = ProductData::from($request->validated());

        $result = $this->createProductProcess->run($payload);

        return $this->created($result->product);
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

        return $this->success($result->product);
    }

    public function destroy(int $id, DeleteProductProcess $process): JsonResponse
    {
        $payload = (object) [
            'id' => $id,
            'product_id' => $id, // Required by ValidateProductExists
        ];

        $process->run($payload);

        return $this->noContent();
    }
}
