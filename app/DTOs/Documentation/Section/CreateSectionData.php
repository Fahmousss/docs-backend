<?php

declare(strict_types=1);

namespace App\DTOs\Documentation\Section;

use Spatie\LaravelData\Data;

final class CreateSectionData extends Data
{
    public ?object $section = null;

    public function __construct(
        public readonly string $productId,
        public readonly string $name,
        public readonly int $sortOrder,
    ) {}
}
