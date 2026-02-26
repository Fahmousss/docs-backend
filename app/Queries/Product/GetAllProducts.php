<?php

declare(strict_types=1);

namespace App\Queries\Product;

use App\Queries\Query;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class GetAllProducts extends Query
{
    public function execute(): Collection
    {
        return DB::table('products')
            ->orderBy('name')
            ->get();
    }
}
