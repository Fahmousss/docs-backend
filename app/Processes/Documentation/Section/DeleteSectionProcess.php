<?php

declare(strict_types=1);

namespace App\Processes\Documentation\Section;

use App\Commands\Documentation\RefreshDocumentationView;
use App\Commands\Documentation\Section\DeleteSection;
use App\Processes\Process;

final class DeleteSectionProcess extends Process
{
    protected array $tasks = [
        DeleteSection::class,
        RefreshDocumentationView::class, // Needs $payload->productId from DeleteSection command
    ];
}
