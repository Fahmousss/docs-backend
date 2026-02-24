<?php

declare(strict_types=1);

namespace App\Processes\Product;

use App\Commands\Product\UpdateProduct;
use App\Commands\Product\ValidateUniqueProductName;
use App\Commands\Shared\ValidateProductExists;
use App\Processes\Process;

final class UpdateProductProcess extends Process
{
    protected array $tasks = [
        ValidateProductExists::class,
        ValidateUniqueProductName::class,
        UpdateProduct::class,
    ];
}
