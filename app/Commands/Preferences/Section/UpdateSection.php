<?php

declare(strict_types=1);

namespace App\Commands\Preferences\Section;

use App\DTOs\Preferences\Section\UpdateSectionData;
use App\Models\PreferenceSection;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class UpdateSection
{
    public function handle(UpdateSectionData $payload, Closure $next): mixed
    {
        $preferenceSection = PreferenceSection::query()->find($payload->id);

        throw_if(! $preferenceSection, ModelNotFoundException::class, 'Preference Section not found.');

        $preferenceSection->update([
            'section_name' => $payload->name,
            'type'         => $payload->type,
            'sort_order'   => $payload->sortOrder,
        ]);

        $payload->preferenceSection = $preferenceSection;
        $payload->productId         = $preferenceSection->product_id;

        return $next($payload);
    }
}
