<?php

declare(strict_types=1);

namespace App\Queries\Documentation;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final readonly class GetProductDocumentation
{
    public function __construct(
        private string $productId
    ) {}

    public function execute(): Collection
    {
        return DB::table('product_docs_view')
            ->where('product_id', $this->productId)
            ->orderBy('section_sort')
            ->orderBy('menu_sort')
            ->orderBy('submenu_sort')
            ->get();
    }
}
