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

        DB::transaction(function () use ($sections, $menus, $submenus): void {

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
