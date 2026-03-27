<?php

declare(strict_types=1);

namespace App\Processes\Documentation\Submenu;

use App\Commands\Documentation\RefreshDocumentationView;
use App\Commands\Documentation\SanitizeDocsContent;
use App\Commands\Documentation\Submenu\UpdateSubmenu;
use App\Processes\Process;

final class UpdateSubmenuProcess extends Process
{
    protected array $tasks = [
        SanitizeDocsContent::class,
        UpdateSubmenu::class,
        RefreshDocumentationView::class,
    ];
}
