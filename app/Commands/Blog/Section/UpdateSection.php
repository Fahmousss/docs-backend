<?php

declare(strict_types=1);

namespace App\Commands\Blog\Section;

use App\DTOs\Blog\Section\UpdateSectionData;
use App\Models\BlogSection;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class UpdateSection
{
    public function handle(UpdateSectionData $payload, Closure $next): mixed
    {
        $blogSection = BlogSection::query()->find($payload->id);

        throw_if(! $blogSection, ModelNotFoundException::class, 'Blog Section not found.');

        $blogSection->update([
            'title'          => $payload->title,
            'publish_date'   => $payload->publishDate,
            'description'    => $payload->description,
            'content'        => $payload->content,
            'hero_image_url' => $payload->heroImageUrl,
            'creators'       => $payload->creators,
            'sort_order'     => $payload->sortOrder,
        ]);

        $payload->blogSection = $blogSection;
        $payload->productId   = $blogSection->product_id;

        return $next($payload);
    }
}
