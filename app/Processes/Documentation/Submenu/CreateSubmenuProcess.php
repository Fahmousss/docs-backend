<?php

declare(strict_types=1);

namespace App\Processes\Documentation\Submenu;

use App\Commands\Documentation\RefreshDocumentationView;
use App\Commands\Documentation\SanitizeDocsContent;
use App\Commands\Documentation\Submenu\CreateSubmenu;
use App\Processes\Process;

final class CreateSubmenuProcess extends Process
{
    protected array $tasks = [
        SanitizeDocsContent::class,
        CreateSubmenu::class,
        RefreshDocumentationView::class,
    ];
}
