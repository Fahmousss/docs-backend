<?php

declare(strict_types=1);

namespace App\DTOs\Preferences\Section;

use Spatie\LaravelData\Data;

final class CreateSectionData extends Data
{
    public ?object $preferenceSection = null;

    public function __construct(
        public readonly string $productId,
        public readonly string $name,
        public readonly string $type,
        public readonly int $sortOrder,
    ) {}
}
