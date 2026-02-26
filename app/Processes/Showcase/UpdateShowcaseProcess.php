<?php

declare(strict_types=1);

namespace App\Processes\Showcase;

use App\Commands\Showcase\RefreshShowcaseView;
use App\Commands\Showcase\SanitizeShowcaseContent;
use App\Commands\Showcase\SyncShowcaseItems;
use App\Processes\Process;

final class UpdateShowcaseProcess extends Process
{
    protected array $tasks = [
        SanitizeShowcaseContent::class,
        SyncShowcaseItems::class,
        RefreshShowcaseView::class,
    ];
}
