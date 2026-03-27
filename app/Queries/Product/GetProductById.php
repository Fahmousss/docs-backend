<?php

declare(strict_types=1);

namespace App\Queries\Product;

use App\Queries\Query;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use stdClass;

final class GetProductById extends Query
{
    public function __construct(
        private string $id
    ) {}

    public function execute(): stdClass
    {
        $product = DB::table('products')
            ->where('id', $this->id)
            ->first();

        throw_if(! $product, ModelNotFoundException::class, 'Product not found.');

        return $product;
    }
}
