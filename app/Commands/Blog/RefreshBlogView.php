<?php

declare(strict_types=1);

namespace App\Commands\Blog;

use App\DTOs\Blog\BlogPayload;
use Closure;
use Illuminate\Support\Facades\DB;

final class RefreshBlogView
{
    public function handle(BlogPayload $payload, Closure $next): mixed
    {
        $response = $next($payload);

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('REFRESH MATERIALIZED VIEW product_blog_view');
        }

        return $response;
    }
}
