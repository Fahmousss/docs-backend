<?php

declare(strict_types=1);

namespace App\Processes;

use Illuminate\Support\Facades\Pipeline;

abstract class Process
{
    /**
     * @var array<int, class-string>
     */
    protected array $tasks = [];

    /**
     * Execute the pipeline with the provided payload.
     *
     * @param  object  $payload  Usually a spatie/laravel-data DTO
     */
    final public function run(object $payload): mixed
    {
        return Pipeline::send(
            passable: $payload,
        )->through(
            pipes: $this->tasks,
        )->thenReturn();
    }
}
