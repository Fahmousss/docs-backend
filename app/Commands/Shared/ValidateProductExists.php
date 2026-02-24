<?php

declare(strict_types=1);

namespace App\Commands\Shared;

use App\Models\Product;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class ValidateProductExists
{
    /**
     * Handle the command.
     *
     * @param  object  $payload  Must have product_id property
     */
    public function handle(object $payload, Closure $next): mixed
    {
        $product = Product::query()->find($payload->product_id);

        if (! $product) {
            throw (new ModelNotFoundException)->setModel(Product::class, [$payload->product_id]);
        }

        $payload->product = $product;

        return $next($payload);
    }
}
