<?php

declare(strict_types=1);

namespace App\Queries\Documentation;

use App\Queries\Query;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class GetMenuSubmenus extends Query
{
    public function __construct(
        private readonly string $productId,
        private readonly string $menuId
    ) {}

    public function execute(): Collection
    {
        return DB::table('submenus')
            ->join('menus', 'submenus.menu_id', '=', 'menus.id')
            ->join('sections', 'menus.section_id', '=', 'sections.id')
            ->where('sections.product_id', $this->productId)
            ->where('submenus.menu_id', $this->menuId)
            ->select('submenus.*')
            ->orderBy('submenus.sort_order')
            ->get();
    }
}
