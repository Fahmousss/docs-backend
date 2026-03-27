<?php

declare(strict_types=1);

namespace App\Commands\Documentation\Submenu;

use App\DTOs\Documentation\Submenu\UpdateSubmenuData;
use App\Models\Submenu;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class UpdateSubmenu
{
    public function handle(UpdateSubmenuData $payload, Closure $next): mixed
    {
        $submenu = Submenu::query()->with('menu.section')->find($payload->id);

        throw_if(! $submenu, ModelNotFoundException::class, 'Submenu not found.');

        $submenu->update([
            'submenu_name' => $payload->name,
            'content'      => $payload->content,
            'sort_order'   => $payload->sortOrder,
        ]);

        $payload->submenu   = $submenu;
        $payload->productId = $submenu->menu->section->product_id;

        return $next($payload);
    }
}
