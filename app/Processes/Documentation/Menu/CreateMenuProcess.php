<?php

declare(strict_types=1);

namespace App\Processes\Documentation\Menu;

use App\Commands\Documentation\Menu\CreateMenu;
use App\Commands\Documentation\RefreshDocumentationView;
use App\Processes\Process;

final class CreateMenuProcess extends Process
{
    protected array $tasks = [
        CreateMenu::class,
        RefreshDocumentationView::class,
    ];
}
