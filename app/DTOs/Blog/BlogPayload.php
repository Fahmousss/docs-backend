<?php

declare(strict_types=1);

namespace App\DTOs\Blog;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class BlogPayload extends Data
{
    /**
     * @param DataCollection<int, BlogSectionData> $sections
     */
    public function __construct(
        public readonly string $productId,
        #[DataCollectionOf(BlogSectionData::class)]
        public readonly DataCollection $sections,
    ) {}
}
