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
        private readonly int $id
    ) {}

    public function get(): stdClass
    {
        $product = DB::table('products')
            ->where('id', $this->id)
            ->first();

        if (! $product) {
            throw (new ModelNotFoundException)->setModel('Product', [$this->id]);
        }

        return $product;
    }
}
