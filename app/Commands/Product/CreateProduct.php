<?php

declare(strict_types=1);

namespace App\Commands\Product;

use App\Models\Product;
use Closure;

final class CreateProduct
{
    /**
     * Handle the command.
     */
    public function handle(object $payload, Closure $next): mixed
    {
        $product = Product::query()->create([
            'name' => $payload->name,
        ]);

        $payload->product = $product;
        $payload->id      = $product->id;

        return $next($payload);
    }
}
