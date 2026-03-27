<?php

declare(strict_types=1);

namespace App\Processes\Documentation\Menu;

use App\Commands\Documentation\Menu\DeleteMenu;
use App\Commands\Documentation\RefreshDocumentationView;
use App\Processes\Process;

final class DeleteMenuProcess extends Process
{
    protected array $tasks = [
        DeleteMenu::class,
        RefreshDocumentationView::class,
    ];
}
