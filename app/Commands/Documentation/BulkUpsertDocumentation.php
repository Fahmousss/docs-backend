<?php

declare(strict_types=1);

namespace App\Commands\Documentation;

use App\DTOs\Documentation\DocumentationPayload;
use App\Models\Menu;
use App\Models\Section;
use App\Models\Submenu;
use Closure;
use Illuminate\Support\Facades\DB;
use Throwable;

final class BulkUpsertDocumentation
{
    /**
     * @throws Throwable
     */
    public function handle(DocumentationPayload $payload, Closure $next): mixed
    {
        $sections = [];
        $menus    = [];
        $submenus = [];

        foreach ($payload->sections as $section) {
            $sections[] = [
                'id'           => $section->id,
                'product_id'   => $payload->productId,
                'section_name' => $section->name,
                'sort_order'   => $section->sortOrder,
            ];

            foreach ($section->menus as $menu) {
                $menus[] = [
                    'id'         => $menu->id,
                    'section_id' => $section->id,
                    'menu_name'  => $menu->name,
                    'sort_order' => $menu->sortOrder,
                ];

                foreach ($menu->submenus as $submenu) {
                    $submenus[] = [
                        'id'           => $submenu->id,
                        'menu_id'      => $menu->id,
                        'submenu_name' => $submenu->name,
                        'content'      => $submenu->content,
                        'sort_order'   => $submenu->sortOrder,
                    ];
                }
            }
        }

        DB::transaction(function () use ($payload, $sections, $menus, $submenus): void {
            $this->deleteRemovedItems($payload->productId, $sections, $menus, $submenus);

            if ($sections !== []) {
                Section::query()->upsert($sections, ['id'], ['section_name', 'sort_order', 'product_id']);
            }

            if ($menus !== []) {
                Menu::query()->upsert($menus, ['id'], ['menu_name', 'sort_order', 'section_id']);
            }

            if ($submenus !== []) {
                Submenu::query()->upsert($submenus, ['id'], ['submenu_name', 'content', 'sort_order', 'menu_id']);
            }
        });

        return $next($payload);
    }

    /**
     * @param  array<int, array{id:string}>  $sections
     * @param  array<int, array{id:string}>  $menus
     * @param  array<int, array{id:string}>  $submenus
     */
    private function deleteRemovedItems(string $productId, array $sections, array $menus, array $submenus): void
    {
        $incomingSectionIds = array_column($sections, 'id');
        $incomingMenuIds    = array_column($menus, 'id');
        $incomingSubmenuIds = array_column($submenus, 'id');

        // 1) Delete submenus removed from UI (scoped by product via joins)
        $submenuQuery = DB::table('submenus as sm')
            ->join('menus as m', 'm.id', '=', 'sm.menu_id')
            ->join('sections as s', 's.id', '=', 'm.section_id')
            ->where('s.product_id', $productId);

        if ($incomingSubmenuIds !== []) {
            $submenuQuery->whereNotIn('sm.id', $incomingSubmenuIds);
        }

        $submenuQuery->delete();

        // 2) Delete menus removed from UI
        $menuQuery = DB::table('menus as m')
            ->join('sections as s', 's.id', '=', 'm.section_id')
            ->where('s.product_id', $productId);

        if ($incomingMenuIds !== []) {
            $menuQuery->whereNotIn('m.id', $incomingMenuIds);
        }

        $menuQuery->delete();

        // 3) Delete sections removed from UI
        $sectionQuery = DB::table('sections as s')
            ->where('s.product_id', $productId);

        if ($incomingSectionIds !== []) {
            $sectionQuery->whereNotIn('s.id', $incomingSectionIds);
        }

        $sectionQuery->delete();
    }
}
