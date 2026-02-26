<?php

declare(strict_types=1);

namespace App\Processes\Preferences;

use App\Commands\Preferences\RefreshPreferencesView;
use App\Commands\Preferences\SanitizePreferencesContent;
use App\Commands\Preferences\SyncPreferencesItems;
use App\Commands\Shared\AssignMissingUuids;
use App\Processes\Process;

final class UpdatePreferencesProcess extends Process
{
    protected array $tasks = [
        AssignMissingUuids::class,
        SanitizePreferencesContent::class,
        SyncPreferencesItems::class,
        RefreshPreferencesView::class,
    ];
}
