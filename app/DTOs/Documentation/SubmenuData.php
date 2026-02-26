<?php

declare(strict_types=1);

namespace App\DTOs\Documentation;

use Spatie\LaravelData\Data;

final class SubmenuData extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public ?string $content,
        public readonly int $sortOrder,
    ) {}
}
