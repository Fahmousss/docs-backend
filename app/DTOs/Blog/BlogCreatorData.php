<?php

declare(strict_types=1);

namespace App\DTOs\Blog;

use Spatie\LaravelData\Data;

final class BlogCreatorData extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $photoUrl,
    ) {}
}
