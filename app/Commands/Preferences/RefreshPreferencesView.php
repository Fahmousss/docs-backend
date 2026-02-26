<?php

declare(strict_types=1);

namespace App\Commands\Preferences;

use App\DTOs\Preferences\PreferencesPayload;
use Closure;
use Illuminate\Support\Facades\DB;

final class RefreshPreferencesView
{
    public function handle(PreferencesPayload $payload, Closure $next): mixed
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('REFRESH MATERIALIZED VIEW product_preferences_view');
        }

        return $next($payload);
    }
}
