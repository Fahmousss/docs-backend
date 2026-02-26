<?php

declare(strict_types=1);

namespace App\Queries;

abstract class Query
{
    abstract public function execute(): mixed;
}
