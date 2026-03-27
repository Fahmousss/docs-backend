<?php

declare(strict_types=1);

namespace App\Commands\Documentation\Menu;

use App\Models\Menu;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class DeleteMenu
{
    public function handle(object $payload, Closure $next): mixed
    {
        $menu = Menu::query()->with('section')->find($payload->id);

        throw_if(! $menu, ModelNotFoundException::class, 'Menu not found.');

        $payload->productId = $menu->section->product_id;

        $menu->delete();

        return $next($payload);
    }
}
