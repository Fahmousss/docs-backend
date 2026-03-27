<?php

declare(strict_types=1);

namespace App\Commands\Preferences\Section;

use App\DTOs\Preferences\Section\CreateSectionData;
use App\Models\PreferenceSection;
use Closure;

final class CreateSection
{
    public function handle(CreateSectionData $payload, Closure $next): mixed
    {
        $preferenceSection = PreferenceSection::query()->create([
            'product_id'   => $payload->productId,
            'section_name' => $payload->name,
            'type'         => $payload->type,
            'sort_order'   => $payload->sortOrder,
        ]);

        $payload->preferenceSection = $preferenceSection;

        return $next($payload);
    }
}
