<?php

declare(strict_types=1);

namespace App\Queries\Preferences;

use App\Queries\Query;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class GetProductPreferences extends Query
{
    public function __construct(private readonly string $productId) {}

    public function execute(): Collection
    {
        return DB::table('product_preferences_view')
            ->where('product_id', $this->productId)
            ->orderBy('section_sort')
            ->orderBy('item_sort')
            ->get();
    }
}
