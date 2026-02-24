<?php

declare(strict_types=1);

namespace App\Queries\Product;

use App\Exceptions\ModelNotFoundException;
use App\Queries\Query;
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
            throw (new ModelNotFoundException)->setModel('Product', [$this->id], sprintf('Product with ID %d not found.', $this->id));
        }

        return $product;
    }
}
