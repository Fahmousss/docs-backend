<?php

declare(strict_types=1);

namespace App\Commands\Documentation\Section;

use App\DTOs\Documentation\Section\UpdateSectionData;
use App\Models\Section;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class UpdateSection
{
    public function handle(UpdateSectionData $payload, Closure $next): mixed
    {
        $section = Section::query()->find($payload->id);

        throw_if(! $section, ModelNotFoundException::class, 'Section not found.');

        $section->update([
            'section_name' => $payload->name,
            'sort_order'   => $payload->sortOrder,
        ]);

        $payload->section   = $section;
        $payload->productId = $section->product_id;

        return $next($payload);
    }
}
