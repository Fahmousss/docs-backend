<?php

declare(strict_types=1);

namespace App\Commands\Showcase;

use App\DTOs\Showcase\ShowcasePayload;
use App\Models\ShowcaseItem;
use Closure;

final class SyncShowcaseItems
{
    public function handle(ShowcasePayload $payload, Closure $next): mixed
    {
        $items = [];

        foreach ($payload->items as $item) {
            $items[] = [
                'id'          => $item->id,
                'product_id'  => $payload->productId,
                'title'       => $item->title,
                'description' => $item->description,
                'media_url'   => $item->mediaUrl,
                'content'     => $item->content,
                'sort_order'  => $item->sortOrder,
            ];
        }

        if ($items !== []) {
            ShowcaseItem::query()->upsert(
                $items,
                ['id'],
                ['title', 'description', 'media_url', 'content', 'sort_order']
            );
        }

        // NOTE: As requested by the user, this endpoint handles PARTIAL UPDATES.
        // It does NOT delete showcase items missing from the payload.
        // It only upserts the items provided in the request.

        return $next($payload);
    }
}
