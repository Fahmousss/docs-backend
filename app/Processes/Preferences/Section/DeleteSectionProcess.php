<?php

declare(strict_types=1);

namespace App\Processes\Preferences\Section;

use App\Commands\Preferences\RefreshPreferencesView;
use App\Commands\Preferences\Section\DeleteSection;
use App\Processes\Process;

final class DeleteSectionProcess extends Process
{
    protected array $tasks = [
        DeleteSection::class,
        RefreshPreferencesView::class,
    ];
}
