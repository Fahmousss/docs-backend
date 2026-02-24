<?php

declare(strict_types=1);

namespace App\Processes\Product;

use App\Commands\Product\CreateProduct;
use App\Commands\Product\ValidateUniqueProductName;
use App\Processes\Process;

final class CreateProductProcess extends Process
{
    protected array $tasks = [
        ValidateUniqueProductName::class,
        CreateProduct::class,
    ];
}
