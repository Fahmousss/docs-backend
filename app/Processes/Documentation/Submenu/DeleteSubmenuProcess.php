<?php

declare(strict_types=1);

namespace App\Processes\Documentation\Submenu;

use App\Commands\Documentation\RefreshDocumentationView;
use App\Commands\Documentation\Submenu\DeleteSubmenu;
use App\Processes\Process;

final class DeleteSubmenuProcess extends Process
{
    protected array $tasks = [
        DeleteSubmenu::class,
        RefreshDocumentationView::class,
    ];
}
