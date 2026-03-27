<?php

declare(strict_types=1);

namespace App\DTOs\Preferences\Item;

use Spatie\LaravelData\Data;

final class CreateItemData extends Data
{
    public ?object $preferenceItem = null;

    public ?string $productId = null;

    public function __construct(
        public readonly string $sectionId,
        public readonly string $name,
        public readonly string $url,
        public readonly ?string $imageUrl,
        public readonly ?string $icon,
        public readonly ?string $content,
        public readonly int $sortOrder,
    ) {}
}
