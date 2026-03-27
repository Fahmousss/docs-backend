<?php

declare(strict_types=1);

namespace App\Commands\Documentation\Submenu;

use App\Models\Submenu;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class DeleteSubmenu
{
    public function handle(object $payload, Closure $next): mixed
    {
        $submenu = Submenu::query()->with('menu.section')->find($payload->id);

        throw_if(! $submenu, ModelNotFoundException::class, 'Submenu not found.');

        $payload->productId = $submenu->menu->section->product_id;

        $submenu->delete();

        return $next($payload);
    }
}
