<?php

declare(strict_types=1);

namespace App\Commands\Documentation;

use App\DTOs\Documentation\DocumentationPayload;
use Closure;
use Illuminate\Support\Facades\DB;

final class RefreshDocumentationView
{
    public function handle(DocumentationPayload $payload, Closure $next): mixed
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('REFRESH MATERIALIZED VIEW product_docs_view');
        }

        return $next($payload);
    }
}
