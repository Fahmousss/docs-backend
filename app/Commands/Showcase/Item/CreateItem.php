<?php

declare(strict_types=1);

namespace App\Commands\Showcase\Item;

use App\DTOs\Showcase\Item\CreateItemData;
use App\Models\ShowcaseItem;
use Closure;

final class CreateItem
{
    public function handle(CreateItemData $payload, Closure $next): mixed
    {
        $showcaseItem = ShowcaseItem::query()->create([
            'product_id'        => $payload->productId,
            'thumbnail_url'     => $payload->thumbnailUrl,
            'title'             => $payload->title,
            'short_description' => $payload->shortDescription,
            'content'           => $payload->content,
            'sort_order'        => $payload->sortOrder,
        ]);

        $payload->showcaseItem = $showcaseItem;

        return $next($payload);
    }
}
