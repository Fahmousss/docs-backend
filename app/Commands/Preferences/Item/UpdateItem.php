<?php

declare(strict_types=1);

namespace App\Commands\Preferences\Item;

use App\DTOs\Preferences\Item\UpdateItemData;
use App\Models\PreferenceItem;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class UpdateItem
{
    public function handle(UpdateItemData $payload, Closure $next): mixed
    {
        $preferenceItem = PreferenceItem::query()->with('section')->find($payload->id);

        throw_if(! $preferenceItem, ModelNotFoundException::class, 'Preference Item not found.');

        $preferenceItem->update([
            'item_name'  => $payload->name,
            'url'        => $payload->url,
            'image_url'  => $payload->imageUrl,
            'icon'       => $payload->icon,
            'content'    => $payload->content,
            'sort_order' => $payload->sortOrder,
        ]);

        $payload->preferenceItem = $preferenceItem;
        $payload->productId      = $preferenceItem->section->product_id;

        return $next($payload);
    }
}
