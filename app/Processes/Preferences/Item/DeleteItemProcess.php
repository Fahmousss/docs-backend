<?php

declare(strict_types=1);

namespace App\Processes\Preferences\Item;

use App\Commands\Preferences\Item\DeleteItem;
use App\Commands\Preferences\RefreshPreferencesView;
use App\Processes\Process;

final class DeleteItemProcess extends Process
{
    protected array $tasks = [
        DeleteItem::class,
        RefreshPreferencesView::class,
    ];
}
