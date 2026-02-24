<?php

declare(strict_types=1);

namespace App\Commands\Product;

use Closure;

final class UpdateProduct
{
    /**
     * Handle the command.
     */
    public function handle(object $payload, Closure $next): mixed
    {
        $payload->product->update([
            'name' => $payload->name,
        ]);

        $payload->product->refresh();

        return $next($payload);
    }
}
