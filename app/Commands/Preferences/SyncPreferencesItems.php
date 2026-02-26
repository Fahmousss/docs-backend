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
        $sections = [];
        $items    = [];

        foreach ($payload->sections as $section) {
            $sections[] = [
                'id'         => $section->id,
                'product_id' => $payload->productId,
                'name'       => $section->name,
                'sort_order' => $section->sortOrder,
            ];

            foreach ($section->items as $item) {
                $items[] = [
                    'id'                    => $item->id,
                    'preference_section_id' => $section->id,
                    'item_name'             => $item->itemName,
                    'content'               => $item->content,
                    'sort_order'            => $item->sortOrder,
                ];
            }
        }

        // NOTE: As requested by the user, this endpoint handles PARTIAL UPDATES like Showcase.
        // It does NOT delete sections or items missing from the payload.
        DB::transaction(function () use ($sections, $items): void {
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
