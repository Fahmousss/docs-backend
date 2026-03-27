<?php

declare(strict_types=1);

namespace App\Processes\Showcase\Item;

use App\Commands\Showcase\Item\DeleteItem;
use App\Commands\Showcase\RefreshShowcaseView;
use App\Processes\Process;

final class DeleteItemProcess extends Process
{
    protected array $tasks = [
        DeleteItem::class,
        RefreshShowcaseView::class,
    ];
}
