<?php

declare(strict_types=1);

namespace App\Commands\Blog\Section;

use App\DTOs\Blog\Section\CreateSectionData;
use App\Models\BlogSection;
use Closure;

final class CreateSection
{
    public function handle(CreateSectionData $payload, Closure $next): mixed
    {
        $blogSection = BlogSection::query()->create([
            'product_id'     => $payload->productId,
            'title'          => $payload->title,
            'publish_date'   => $payload->publishDate,
            'description'    => $payload->description,
            'content'        => $payload->content,
            'hero_image_url' => $payload->heroImageUrl,
            'creators'       => $payload->creators,
            'sort_order'     => $payload->sortOrder,
        ]);

        $payload->blogSection = $blogSection;

        return $next($payload);
    }
}
