<?php

declare(strict_types=1);

namespace App\DTOs\Showcase;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class ShowcasePayload extends Data
{
    /**
     * @param DataCollection<int, ShowcaseItemData> $items
     */
    public function __construct(
        public readonly string $productId,
        #[DataCollectionOf(ShowcaseItemData::class)]
        public readonly DataCollection $items,
    ) {}
}
