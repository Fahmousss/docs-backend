<?php

declare(strict_types=1);

namespace App\DTOs\Preferences;

use Spatie\LaravelData\Data;

final class PreferenceItemData extends Data
{
    public function __construct(
        public ?string $id,
        public readonly string $itemName,
        public ?string $content,
        public readonly int $sortOrder,
    ) {}
}
