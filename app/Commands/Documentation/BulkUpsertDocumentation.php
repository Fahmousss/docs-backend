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
        $sections   = [];
        $menus      = [];
        $submenus   = [];
        $sectionIds = [];
        $menuIds    = [];
        $submenuIds = [];

        foreach ($payload->sections as $section) {
            $sections[] = [
                'id'           => $section->id,
                'product_id'   => $payload->productId,
                'section_name' => $section->name,
                'sort_order'   => $section->sortOrder,
            ];
            $sectionIds[] = $section->id;

            foreach ($section->menus as $menu) {
                $menus[] = [
                    'id'         => $menu->id,
                    'section_id' => $section->id,
                    'menu_name'  => $menu->name,
                    'sort_order' => $menu->sortOrder,
                ];
                $menuIds[] = $menu->id;

                foreach ($menu->submenus as $submenu) {
                    $submenus[] = [
                        'id'           => $submenu->id,
                        'menu_id'      => $menu->id,
                        'submenu_name' => $submenu->name,
                        'content'      => $submenu->content,
                        'sort_order'   => $submenu->sortOrder,
                    ];
                    $submenuIds[] = $submenu->id;
                }
            }
        }

        DB::transaction(function () use ($sections, $menus, $submenus, $sectionIds, $menuIds, $submenuIds, $payload): void {

            Section::query()
                ->where('product_id', $payload->productId)
                ->whereNotIn('id', $sectionIds)
                ->delete();

            Menu::query()
                ->whereHas('section', function (mixed $query) use ($payload): void {
                    $query->where('product_id', $payload->productId);
                })
                ->whereNotIn('id', $menuIds)
                ->delete();

            Submenu::query()
                ->whereHas('menu.section', function (mixed $query) use ($payload): void {
                    $query->where('product_id', $payload->productId);
                })
                ->whereNotIn('id', $submenuIds)
                ->delete();

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
}
