<?php

declare(strict_types=1);

namespace App\Commands\Blog;

use App\DTOs\Blog\BlogPayload;
use App\Models\BlogSection;
use Closure;
use Illuminate\Support\Facades\DB;

final class BulkUpsertBlogSections
{
    public function handle(BlogPayload $payload, Closure $next): mixed
    {
        $sections          = [];
        $payloadSectionIds = [];

        foreach ($payload->sections as $section) {
            $payloadSectionIds[] = $section->id;

            $sections[] = [
                'id'             => $section->id,
                'product_id'     => $payload->productId,
                'title'          => $section->title,
                'publish_date'   => $section->publishDate,
                'description'    => $section->description,
                'content'        => $section->content,
                'hero_image_url' => $section->heroImageUrl,
                'creators'       => json_encode($section->creators->toArray()), // Manual cast for upsert
                'sort_order'     => $section->sortOrder,
            ];
        }

        DB::transaction(function () use ($payload, $sections, $payloadSectionIds): void {
            // 1. Delete sections that are in the database for this product but no longer in the payload
            BlogSection::query()
                ->where('product_id', $payload->productId)
                ->whereNotIn('id', $payloadSectionIds)
                ->delete();

            // 2. Upsert the provided sections
            if ($sections !== []) {
                BlogSection::query()->upsert(
                    $sections,
                    ['id'],
                    [
                        'title',
                        'publish_date',
                        'description',
                        'content',
                        'hero_image_url',
                        'creators',
                        'sort_order',
                        'product_id',
                    ]
                );
            }
        });

        return $next($payload);
    }
}
