W<?php

declare(strict_types=1);

namespace App\Commands\Showcase;

use App\DTOs\Showcase\ShowcasePayload;
use Closure;
use Illuminate\Support\Str;

final class AssignMissingUuids
{
    public function handle(ShowcasePayload $payload, Closure $next): mixed
    {
        foreach ($payload->items as $item) {
            $item->id ??= Str::uuid()->toString();
        }

        return $next($payload);
    }
}
