<?php

declare(strict_types=1);

namespace App\Processes\Product;

use App\Commands\Product\DeleteProduct;
use App\Commands\Shared\ValidateProductExists;
use App\Processes\Process;

final class DeleteProductProcess extends Process
{
    protected array $tasks = [
        ValidateProductExists::class,
        DeleteProduct::class,
    ];
}
