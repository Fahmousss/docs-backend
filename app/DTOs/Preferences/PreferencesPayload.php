<?php

declare(strict_types=1);

namespace App\DTOs\Preferences;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class PreferencesPayload extends Data
{
    /**
     * @param DataCollection<int, PreferenceSectionData> $sections
     */
    public function __construct(
        public readonly string $productId,
        #[DataCollectionOf(PreferenceSectionData::class)]
        public readonly DataCollection $sections,
    ) {}
}
