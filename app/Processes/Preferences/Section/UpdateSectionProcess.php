<?php

declare(strict_types=1);

namespace App\Processes\Preferences\Section;

use App\Commands\Preferences\RefreshPreferencesView;
use App\Commands\Preferences\Section\UpdateSection;
use App\Processes\Process;

final class UpdateSectionProcess extends Process
{
    protected array $tasks = [
        UpdateSection::class,
        RefreshPreferencesView::class,
    ];
}
