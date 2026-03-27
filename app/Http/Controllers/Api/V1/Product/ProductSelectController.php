<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductSelectResource;
use App\Queries\Product\GetAllProductsSelect;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

final class ProductSelectController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $query = new GetAllProductsSelect();

        /** @var Collection $products */
        $products = $query->execute();

        Log::info($products);

        return response()->json([
            'success' => true,
            'message' => 'Products retrieved successfully',
            'data'    => ProductSelectResource::collection($products),
        ]);
    }
}
