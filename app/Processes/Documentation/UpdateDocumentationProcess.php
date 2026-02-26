<?php

declare(strict_types=1);

namespace App\Processes\Documentation;

use App\Commands\Documentation\BulkUpsertDocumentation;
use App\Commands\Documentation\RefreshDocumentationView;
use App\Commands\Documentation\SanitizeDocsContent;
use App\Processes\Process;

final class UpdateDocumentationProcess extends Process
{
    /**
     * @var array<int, class-string>
     */
    protected array $tasks = [
        SanitizeDocsContent::class,
        BulkUpsertDocumentation::class,
        RefreshDocumentationView::class,
    ];
}
