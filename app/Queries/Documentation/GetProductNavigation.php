<?php

declare(strict_types=1);

namespace App\Queries\Documentation;

use App\Queries\Query;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class GetProductNavigation extends Query
{
    public function __construct(
        private string $productId
    ) {}

    public function execute(): Collection
    {
        return DB::table('sections')
            ->leftJoin('menus', 'menus.section_id', '=', 'sections.id')
            ->leftJoin('submenus', 'submenus.menu_id', '=', 'menus.id')
            ->where('sections.product_id', $this->productId)
            ->orderBy('sections.sort_order')
            ->orderBy('menus.sort_order')
            ->orderBy('submenus.sort_order')
            ->select([
                'sections.id as section_id',
                'sections.section_name',
                'sections.sort_order as section_sort',
                'menus.id as menu_id',
                'menus.menu_name',
                'menus.sort_order as menu_sort',
                'submenus.id as submenu_id',
                'submenus.submenu_name',
                'submenus.sort_order as submenu_sort',
            ])
            ->get();
    }
}
