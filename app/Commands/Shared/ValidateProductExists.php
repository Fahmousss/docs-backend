<?php

declare(strict_types=1);

namespace App\Commands\Shared;

use App\Models\Product;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

final class ValidateProductExists
{
    /**
     * Handle the command.
     *
     * @param object $payload Must have a product_id property
     *
     * @throws Throwable
     */
    public function handle(object $payload, Closure $next): mixed
    {
        $product = Product::query()->find($payload->product_id);

        throw_if(! $product, ModelNotFoundException::class, 'Product not found.');

        $payload->product = $product;

        return $next($payload);
    }
}
