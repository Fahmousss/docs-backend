<?php

declare(strict_types=1);

namespace App\Processes\Preferences\Item;

use App\Commands\Preferences\Item\CreateItem;
use App\Commands\Preferences\RefreshPreferencesView;
use App\Processes\Process;

final class CreateItemProcess extends Process
{
    protected array $tasks = [
        CreateItem::class,
        RefreshPreferencesView::class,
    ];
}
