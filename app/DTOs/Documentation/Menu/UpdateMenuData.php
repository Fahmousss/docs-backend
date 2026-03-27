<?php

declare(strict_types=1);

namespace App\DTOs\Documentation\Menu;

use Spatie\LaravelData\Data;

final class UpdateMenuData extends Data
{
    public ?object $menu = null;

    public ?string $productId = null;

    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly int $sortOrder,
    ) {}
}
