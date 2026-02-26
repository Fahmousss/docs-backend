<?php

declare(strict_types=1);

namespace App\Queries\Showcase;

use App\Queries\Query;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class GetProductShowcase extends Query
{
    public function __construct(private readonly string $productId) {}

    public function execute(): Collection
    {
        return DB::table('product_showcase_view')
            ->where('product_id', $this->productId)
            ->orderBy('sort_order')
            ->get();
    }
}
