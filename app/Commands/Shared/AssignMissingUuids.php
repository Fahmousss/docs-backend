<?php

declare(strict_types=1);

namespace App\Commands\Shared;

use Closure;
use Illuminate\Support\Str;

final class AssignMissingUuids
{
    public function handle(mixed $payload, Closure $next): mixed
    {
        $this->traverseAndAssign($payload);

        return $next($payload);
    }

    private function traverseAndAssign(mixed $data): void
    {
        if (is_iterable($data)) {
            foreach ($data as $item) {
                $this->traverseAndAssign($item);
            }

            return;
        }

        if (is_object($data)) {
            if (property_exists($data, 'id') && $data->id === null) {
                $data->id = Str::uuid()->toString();
            }

            foreach (get_object_vars($data) as $value) {
                if (is_object($value) || is_iterable($value)) {
                    $this->traverseAndAssign($value);
                }
            }
        }
    }
}
