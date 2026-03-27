<?php

declare(strict_types=1);

namespace App\Processes\Preferences\Item;

use App\Commands\Preferences\Item\UpdateItem;
use App\Commands\Preferences\RefreshPreferencesView;
use App\Processes\Process;

final class UpdateItemProcess extends Process
{
    protected array $tasks = [
        UpdateItem::class,
        RefreshPreferencesView::class,
    ];
}
