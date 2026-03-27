<?php

declare(strict_types=1);

namespace App\Commands\Documentation\Section;

use App\DTOs\Documentation\Section\CreateSectionData;
use App\Models\Section;
use Closure;

final class CreateSection
{
    public function handle(CreateSectionData $payload, Closure $next): mixed
    {
        $section = Section::query()->create([
            'product_id'   => $payload->productId,
            'section_name' => $payload->name,
            'sort_order'   => $payload->sortOrder,
        ]);

        // RefreshDocumentationView expects $payload->productId, which already exists on CreateSectionData.
        $payload->section = $section;

        return $next($payload);
    }
}
