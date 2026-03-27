<?php

declare(strict_types=1);

namespace App\Processes\Preferences\Section;

use App\Commands\Preferences\RefreshPreferencesView;
use App\Commands\Preferences\Section\CreateSection;
use App\Processes\Process;

final class CreateSectionProcess extends Process
{
    protected array $tasks = [
        CreateSection::class,
        RefreshPreferencesView::class,
    ];
}
