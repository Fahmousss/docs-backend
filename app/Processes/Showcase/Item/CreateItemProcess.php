<?php

declare(strict_types=1);

namespace App\Processes\Showcase\Item;

use App\Commands\Showcase\Item\CreateItem;
use App\Commands\Showcase\RefreshShowcaseView;
use App\Commands\Showcase\SanitizeShowcaseContent;
use App\Processes\Process;

final class CreateItemProcess extends Process
{
    protected array $tasks = [
        SanitizeShowcaseContent::class,
        CreateItem::class,
        RefreshShowcaseView::class,
    ];
}
