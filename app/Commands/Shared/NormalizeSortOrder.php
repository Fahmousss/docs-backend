<?php

declare(strict_types=1);

namespace App\Commands\Shared;

use Closure;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

final class NormalizeSortOrder
{
    /**
     * Handle the command.
     *
     * @param  object  $payload  Must have sort_order, parent_id, parent_field, and model_class properties
     */
    public function handle(object $payload, Closure $next): mixed
    {
        if (! empty($payload->sort_order)) {
            return $next($payload);
        }

        throw_if(! isset($payload->model_class) || ! is_subclass_of($payload->model_class, Model::class), InvalidArgumentException::class, 'Payload must contain a valid Eloquent model_class.');

        throw_if(! isset($payload->parent_id) || ! isset($payload->parent_field), InvalidArgumentException::class, 'Payload must contain parent_id and parent_field.');

        /** @var Model $model */
        $model = new $payload->model_class;

        $maxSortOrder = $model::query()
            ->where($payload->parent_field, $payload->parent_id)
            ->max('sort_order');

        $payload->sort_order = ($maxSortOrder ?? 0) + 1;

        return $next($payload);
    }
}
