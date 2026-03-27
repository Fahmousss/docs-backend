<?php

declare(strict_types=1);

namespace App\Processes\Documentation\Section;

use App\Commands\Documentation\RefreshDocumentationView;
use App\Commands\Documentation\Section\CreateSection;
use App\Processes\Process;

final class CreateSectionProcess extends Process
{
    protected array $tasks = [
        CreateSection::class,
        RefreshDocumentationView::class,
    ];
}
