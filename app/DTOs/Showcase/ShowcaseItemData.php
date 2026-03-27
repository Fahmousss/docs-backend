<?php

declare(strict_types=1);

namespace App\DTOs\Showcase;

use Spatie\LaravelData\Data;

final class ShowcaseItemData extends Data
{
    public function __construct(
        public ?string $id,
        public readonly string $title,
        public readonly ?string $description,
        public readonly ?string $mediaUrl,
        public readonly ?string $publishDate,
        public ?string $content,
        public readonly int $sortOrder,
    ) {}
}
