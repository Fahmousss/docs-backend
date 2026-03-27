<?php

declare(strict_types=1);

namespace App\DTOs\Documentation\Section;

use Spatie\LaravelData\Data;

final class UpdateSectionData extends Data
{
    public ?string $productId = null;

    public ?object $section = null;

    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly int $sortOrder,
    ) {}
}
