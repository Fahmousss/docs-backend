<?php

declare(strict_types=1);

namespace App\Commands\Showcase;

use App\DTOs\Showcase\ShowcasePayload;
use Closure;
use Illuminate\Support\Facades\DB;

final class RefreshShowcaseView
{
    public function handle(ShowcasePayload $payload, Closure $next): mixed
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('REFRESH MATERIALIZED VIEW product_showcase_view');
        }

        return $next($payload);
    }
}
