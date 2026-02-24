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

        if (! isset($payload->model_class) || ! is_subclass_of($payload->model_class, Model::class)) {
            throw new InvalidArgumentException('Payload must contain a valid Eloquent model_class.');
        }

        if (! isset($payload->parent_id) || ! isset($payload->parent_field)) {
            throw new InvalidArgumentException('Payload must contain parent_id and parent_field.');
        }

        /** @var Model $model */
        $model = new $payload->model_class;

        $maxSortOrder = $model::query()
            ->where($payload->parent_field, $payload->parent_id)
            ->max('sort_order');

        $payload->sort_order = ($maxSortOrder ?? 0) + 1;

        return $next($payload);
    }
}
