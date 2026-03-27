<?php

declare(strict_types=1);

namespace App\Commands\Preferences\Item;

use App\Models\PreferenceItem;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class DeleteItem
{
    public function handle(object $payload, Closure $next): mixed
    {
        $preferenceItem = PreferenceItem::query()->with('section')->find($payload->id);

        throw_if(! $preferenceItem, ModelNotFoundException::class, 'Preference Item not found.');

        $payload->productId = $preferenceItem->section->product_id;

        $preferenceItem->delete();

        return $next($payload);
    }
}
