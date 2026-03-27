<?php

declare(strict_types=1);

namespace App\Commands\Documentation\Menu;

use App\DTOs\Documentation\Menu\CreateMenuData;
use App\Models\Menu;
use App\Models\Section;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class CreateMenu
{
    public function handle(CreateMenuData $payload, Closure $next): mixed
    {
        // Must grab the parent section to ensure it exists and to get the productId
        $section = Section::query()->find($payload->sectionId);
        throw_if(! $section, ModelNotFoundException::class, 'Parent Section not found.');

        $menu = Menu::query()->create([
            'section_id' => $payload->sectionId,
            'menu_name'  => $payload->name,
            'sort_order' => $payload->sortOrder,
        ]);

        $payload->menu      = $menu;
        $payload->productId = $section->product_id;

        return $next($payload);
    }
}
