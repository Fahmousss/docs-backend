<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Documentation;

use App\Http\Controllers\Api\ApiController;
use App\Queries\Documentation\GetProductNavigation;
use Illuminate\Http\JsonResponse;

final class NavigationController extends ApiController
{
    /**
     * Get product documentation navigation tree (sections, menus, submenus without content)
     *
     * @unauthenticated
     *
     * @response array{
     *   success: bool,
     *   message: string,
     *   data: array{
     *     sections: array<int, array{
     *       id: string,
     *       section_name: string,
     *       section_sort: int,
     *       menus: array<int, array{
     *         id: string,
     *         menu_name: string,
     *         menu_sort: int,
     *         submenus: array<int, array{ id: string, submenu_name: string, submenu_sort: int }>
     *       }>
     *     }>
     *   }
     * }
     */
    public function __invoke(string $productId): JsonResponse
    {
        $rows = (new GetProductNavigation($productId))->execute();

        $sections = $rows
            ->groupBy('section_id')
            ->map(function ($sectionRows): array {
                $first = $sectionRows->first();

                $menus = $sectionRows
                    ->filter(fn ($row) => $row->menu_id !== null)
                    ->groupBy('menu_id')
                    ->map(function ($menuRows): array {
                        $firstMenu = $menuRows->first();

                        $submenus = $menuRows
                            ->filter(fn ($row) => $row->submenu_id !== null)
                            ->map(fn ($row) => [
                                'id'           => (string) $row->submenu_id,
                                'submenu_name' => (string) $row->submenu_name,
                                'sort_order'   => (int) $row->submenu_sort,
                            ])
                            ->values();

                        return [
                            'id'         => (string) $firstMenu->menu_id,
                            'menu_name'  => (string) $firstMenu->menu_name,
                            'sort_order' => (int) $firstMenu->menu_sort,
                            'submenus'   => $submenus,
                        ];
                    })
                    ->values();

                return [
                    'id'           => (string) $first->section_id,
                    'section_name' => (string) $first->section_name,
                    'sort_order'   => (int) $first->section_sort,
                    'menus'        => $menus,
                ];
            })
            ->values();

        return $this->success(
            ['sections' => $sections],
            'Navigation retrieved successfully'
        );
    }
}
