<?php

declare(strict_types=1);

namespace App\Commands\Preferences;

use App\DTOs\Preferences\PreferencesPayload;
use App\Models\PreferenceItem;
use App\Models\PreferenceSection;
use Closure;
use Illuminate\Support\Facades\DB;

final class SyncPreferencesItems
{
    public function handle(PreferencesPayload $payload, Closure $next): mixed
    {
        $sections   = [];
        $items      = [];
        $sectionIds = [];
        $itemIds    = [];

        foreach ($payload->sections as $section) {
            $sections[] = [
                'id'         => $section->id,
                'product_id' => $payload->productId,
                'name'       => $section->name,
                'sort_order' => $section->sortOrder,
            ];

            $sectionIds[] = $section->id;

            foreach ($section->items as $item) {
                $items[] = [
                    'id'                    => $item->id,
                    'preference_section_id' => $section->id,
                    'item_name'             => $item->itemName,
                    'content'               => $item->content,
                    'sort_order'            => $item->sortOrder,
                ];
                $itemIds[] = $item->id;
            }
        }

        DB::transaction(function () use ($sections, $items, $sectionIds, $itemIds, $payload): void {
            PreferenceSection::query()
                ->where('product_id', $payload->productId)
                ->whereNotIn('id', $sectionIds)
                ->delete();

            PreferenceItem::query()
                ->whereHas('section', function (mixed $query) use ($payload): void {
                    $query->where('product_id', $payload->productId);
                })
                ->whereNotIn('id', $itemIds)
                ->delete();

            if ($sections !== []) {
                PreferenceSection::query()->upsert(
                    $sections,
                    ['id'],
                    ['name', 'sort_order']
                );
            }

            if ($items !== []) {
                PreferenceItem::query()->upsert(
                    $items,
                    ['id'],
                    ['item_name', 'content', 'sort_order']
                );
            }
        });

        return $next($payload);
    }
}
