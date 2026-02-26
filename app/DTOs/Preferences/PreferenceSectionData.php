<?php

declare(strict_types=1);

namespace App\DTOs\Preferences;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class PreferenceSectionData extends Data
{
    /**
     * @param DataCollection<int, PreferenceItemData> $items
     */
    public function __construct(
        public ?string $id,
        public readonly string $name,
        public readonly int $sortOrder,
        #[DataCollectionOf(PreferenceItemData::class)]
        public readonly DataCollection $items,
    ) {}
}
