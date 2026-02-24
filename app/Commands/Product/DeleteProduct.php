<?php

declare(strict_types=1);

namespace App\Commands\Product;

use Closure;

final class DeleteProduct
{
    /**
     * Handle the command.
     */
    public function handle(object $payload, Closure $next): mixed
    {
        $payload->product->delete();

        return $next($payload);
    }
}
