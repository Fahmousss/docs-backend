<?php

declare(strict_types=1);

namespace App\Processes\Blog\Section;

use App\Commands\Blog\RefreshBlogView;
use App\Commands\Blog\SanitizeBlogContent;
use App\Commands\Blog\Section\UpdateSection;
use App\Processes\Process;

final class UpdateSectionProcess extends Process
{
    protected array $tasks = [
        SanitizeBlogContent::class,
        UpdateSection::class,
        RefreshBlogView::class,
    ];
}
