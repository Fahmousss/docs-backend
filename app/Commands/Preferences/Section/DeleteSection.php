<?php

declare(strict_types=1);

namespace App\Commands\Preferences\Section;

use App\Models\PreferenceSection;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class DeleteSection
{
    public function handle(object $payload, Closure $next): mixed
    {
        $preferenceSection = PreferenceSection::query()->find($payload->id);

        throw_if(! $preferenceSection, ModelNotFoundException::class, 'Preference Section not found.');

        $payload->productId = $preferenceSection->product_id;

        $preferenceSection->delete();

        return $next($payload);
    }
}
