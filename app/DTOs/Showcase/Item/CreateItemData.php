<?php

declare(strict_types=1);

namespace App\DTOs\Showcase\Item;

use Spatie\LaravelData\Data;

final class CreateItemData extends Data
{
    public ?object $showcaseItem = null;

    public function __construct(
        public readonly string $productId,
        public readonly string $thumbnailUrl,
        public readonly string $title,
        public readonly string $shortDescription,
        public readonly string $content,
        public readonly int $sortOrder,
    ) {}
}
