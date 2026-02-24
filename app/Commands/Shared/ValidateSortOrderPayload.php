<?php

declare(strict_types=1);

namespace App\Commands\Shared;

use Closure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class ValidateSortOrderPayload
{
    /**
     * Handle the command.
     *
     * @param  object  $payload  Must have items property
     */
    public function handle(object $payload, Closure $next): mixed
    {
        $validator = Validator::make(
            ['items' => $payload->items],
            [
                'items' => ['required', 'array'],
                'items.*.id' => ['required', 'integer'],
                'items.*.sort_order' => ['required', 'integer'],
            ]
        );

        throw_if($validator->fails(), ValidationException::class, $validator);

        return $next($payload);
    }
}
