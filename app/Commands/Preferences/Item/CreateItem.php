<?php

declare(strict_types=1);

namespace App\Commands\Preferences\Item;

use App\DTOs\Preferences\Item\CreateItemData;
use App\Models\PreferenceItem;
use App\Models\PreferenceSection;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class CreateItem
{
    public function handle(CreateItemData $payload, Closure $next): mixed
    {
        $section = PreferenceSection::query()->find($payload->sectionId);

        throw_if(! $section, ModelNotFoundException::class, 'Parent Preference Section not found.');

        $preferenceItem = PreferenceItem::query()->create([
            'preference_section_id' => $payload->sectionId,
            'item_name'             => $payload->name,
            'url'                   => $payload->url,
            'image_url'             => $payload->imageUrl,
            'icon'                  => $payload->icon,
            'content'               => $payload->content,
            'sort_order'            => $payload->sortOrder,
        ]);

        $payload->preferenceItem = $preferenceItem;
        $payload->productId      = $section->product_id;

        return $next($payload);
    }
}
