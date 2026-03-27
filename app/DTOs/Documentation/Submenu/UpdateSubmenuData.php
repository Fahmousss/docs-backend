<?php

declare(strict_types=1);

namespace App\DTOs\Documentation\Submenu;

use Spatie\LaravelData\Data;

final class UpdateSubmenuData extends Data
{
    public ?object $submenu = null;

    public ?string $productId = null;

    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly ?string $content,
        public readonly int $sortOrder,
    ) {}
}
