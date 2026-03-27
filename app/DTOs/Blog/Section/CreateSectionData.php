<?php

declare(strict_types=1);

namespace App\DTOs\Blog\Section;

use Spatie\LaravelData\Data;

final class CreateSectionData extends Data
{
    public ?object $blogSection = null;

    public function __construct(
        public readonly string $productId,
        public readonly string $title,
        public readonly string $publishDate,
        public readonly ?string $description,
        public readonly ?string $content,
        public readonly ?string $heroImageUrl,
        /**
         * @var array<int, array{name: string, photoUrl: null|string}>
         */
        public readonly array $creators,
        public readonly int $sortOrder,
    ) {}
}
