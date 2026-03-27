<?php

declare(strict_types=1);

namespace App\Processes\Showcase\Item;

use App\Commands\Showcase\Item\UpdateItem;
use App\Commands\Showcase\RefreshShowcaseView;
use App\Commands\Showcase\SanitizeShowcaseContent;
use App\Processes\Process;

final class UpdateItemProcess extends Process
{
    protected array $tasks = [
        SanitizeShowcaseContent::class,
        UpdateItem::class,
        RefreshShowcaseView::class,
    ];
}
