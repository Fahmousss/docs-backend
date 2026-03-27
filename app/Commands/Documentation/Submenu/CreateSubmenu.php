<?php

declare(strict_types=1);

namespace App\Commands\Documentation\Submenu;

use App\DTOs\Documentation\Submenu\CreateSubmenuData;
use App\Models\Menu;
use App\Models\Submenu;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class CreateSubmenu
{
    public function handle(CreateSubmenuData $payload, Closure $next): mixed
    {
        $menu = Menu::query()->with('section')->find($payload->menuId);

        throw_if(! $menu, ModelNotFoundException::class, 'Parent Menu not found.');

        $submenu = Submenu::query()->create([
            'menu_id'      => $payload->menuId,
            'submenu_name' => $payload->name,
            'content'      => $payload->content,
            'sort_order'   => $payload->sortOrder,
        ]);

        $payload->submenu   = $submenu;
        $payload->productId = $menu->section->product_id;

        return $next($payload);
    }
}
