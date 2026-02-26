<?php

declare(strict_types=1);

namespace App\DTOs\Blog;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class BlogSectionData extends Data
{
    /**
     * @param DataCollection<int, BlogCreatorData> $creators
     */
    public function __construct(
        public ?string $id,
        public readonly string $title,
        public readonly string $publishDate,
        public readonly ?string $description,
        public ?string $content,
        public readonly ?string $heroImageUrl,
        #[DataCollectionOf(BlogCreatorData::class)]
        public readonly DataCollection $creators,
        public readonly int $sortOrder,
    ) {}
}
