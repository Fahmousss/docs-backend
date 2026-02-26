<?php

declare(strict_types=1);

namespace App\DTOs\Documentation;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class MenuData extends Data
{
    /**
     * @param DataCollection<int, SubmenuData> $submenus
     */
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly int $sortOrder,
        #[DataCollectionOf(SubmenuData::class)]
        public readonly DataCollection $submenus,
    ) {}
}
