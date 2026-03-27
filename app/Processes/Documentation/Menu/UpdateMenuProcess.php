<?php

declare(strict_types=1);

namespace App\Processes\Documentation\Menu;

use App\Commands\Documentation\Menu\UpdateMenu;
use App\Commands\Documentation\RefreshDocumentationView;
use App\Processes\Process;

final class UpdateMenuProcess extends Process
{
    protected array $tasks = [
        UpdateMenu::class,
        RefreshDocumentationView::class,
    ];
}
