<?php

declare(strict_types=1);

namespace App\Processes\Documentation\Section;

use App\Commands\Documentation\RefreshDocumentationView;
use App\Commands\Documentation\Section\UpdateSection;
use App\Processes\Process;

final class UpdateSectionProcess extends Process
{
    protected array $tasks = [
        UpdateSection::class,
        RefreshDocumentationView::class,
    ];
}
