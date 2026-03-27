<?php

declare(strict_types=1);

namespace App\Commands\Showcase;

use App\DTOs\Showcase\ShowcasePayload;
use App\Models\ShowcaseItem;
use Closure;
use Illuminate\Support\Facades\DB;

final class SyncShowcaseItems
{
    public function handle(ShowcasePayload $payload, Closure $next): mixed
    {
        $items   = [];
        $itemIds = [];

        foreach ($payload->items as $item) {
            $items[] = [
                'id'           => $item->id,
                'product_id'   => $payload->productId,
                'title'        => $item->title,
                'description'  => $item->description,
                'media_url'    => $item->mediaUrl,
                'publish_date' => $item->publishDate,
                'content'      => $item->content,
                'sort_order'   => $item->sortOrder,
            ];
            $itemIds[] = $item->id;
        }

        DB::transaction(function () use ($items, $itemIds, $payload): void {
            ShowcaseItem::query()
                ->where('product_id', $payload->productId)
                ->whereNotIn('id', $itemIds)
                ->delete();

            if ($items !== []) {
                ShowcaseItem::query()->upsert(
                    $items,
                    ['id'],
                    ['title', 'description', 'media_url', 'publish_date', 'content', 'sort_order']
                );
            }
        });

        return $next($payload);
    }
}
