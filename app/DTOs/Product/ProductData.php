<?php

declare(strict_types=1);

namespace App\DTOs\Product;

use Spatie\LaravelData\Data;

final class ProductData extends Data
{
    public function __construct(
        public ?int $id,
        public string $name,
        public ?int $product_id = null,
    ) {}
}
