<?php

declare(strict_types=1);

namespace App\Commands\Documentation\Menu;

use App\DTOs\Documentation\Menu\UpdateMenuData;
use App\Models\Menu;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class UpdateMenu
{
    public function handle(UpdateMenuData $payload, Closure $next): mixed
    {
        // Load the relationship to grab productId directly from the section
        $menu = Menu::query()->with('section')->find($payload->id);

        throw_if(! $menu, ModelNotFoundException::class, 'Menu not found.');

        $menu->update([
            'menu_name'  => $payload->name,
            'sort_order' => $payload->sortOrder,
        ]);

        $payload->menu      = $menu;
        $payload->productId = $menu->section->product_id;

        return $next($payload);
    }
}
