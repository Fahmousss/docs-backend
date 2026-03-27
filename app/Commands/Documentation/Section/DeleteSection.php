<?php

declare(strict_types=1);

namespace App\Commands\Documentation\Section;

use App\Models\Section;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class DeleteSection
{
    public function handle(object $payload, Closure $next): mixed
    {
        $section = Section::query()->find($payload->id);

        throw_if(! $section, ModelNotFoundException::class, 'Section not found.');

        // Pass product_id downstream so the RefreshView command knows which view to refresh
        $payload->productId = $section->product_id;

        $section->delete();

        return $next($payload);
    }
}
