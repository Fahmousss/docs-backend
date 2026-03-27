<?php

declare(strict_types=1);

namespace App\Commands\Showcase\Item;

use App\DTOs\Showcase\Item\UpdateItemData;
use App\Models\ShowcaseItem;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class UpdateItem
{
    public function handle(UpdateItemData $payload, Closure $next): mixed
    {
        $showcaseItem = ShowcaseItem::query()->find($payload->id);

        throw_if(! $showcaseItem, ModelNotFoundException::class, 'Showcase Item not found.');

        $showcaseItem->update([
            'thumbnail_url'     => $payload->thumbnailUrl,
            'title'             => $payload->title,
            'short_description' => $payload->shortDescription,
            'content'           => $payload->content,
            'sort_order'        => $payload->sortOrder,
        ]);

        $payload->showcaseItem = $showcaseItem;
        $payload->productId    = $showcaseItem->product_id;

        return $next($payload);
    }
}
