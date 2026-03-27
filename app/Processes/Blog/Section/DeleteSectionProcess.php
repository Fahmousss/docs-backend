<?php

declare(strict_types=1);

namespace App\Processes\Blog\Section;

use App\Commands\Blog\RefreshBlogView;
use App\Commands\Blog\Section\DeleteSection;
use App\Processes\Process;

final class DeleteSectionProcess extends Process
{
    protected array $tasks = [
        DeleteSection::class,
        RefreshBlogView::class,
    ];
}
