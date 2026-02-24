<?php

declare(strict_types=1);

namespace App\Commands\Product;

use App\Models\Product;
use Closure;
use Illuminate\Validation\ValidationException;

final class ValidateUniqueProductName
{
    /**
     * Handle the command.
     */
    public function handle(object $payload, Closure $next): mixed
    {
        $query = Product::query()->where('name', $payload->name);

        if (isset($payload->id)) {
            $query->where('id', '!=', $payload->id);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'name' => ['The product name has already been taken.'],
            ]);
        }

        return $next($payload);
    }
}
