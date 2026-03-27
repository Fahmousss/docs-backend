<?php

declare(strict_types=1);

namespace App\Processes\Blog\Section;

use App\Commands\Blog\RefreshBlogView;
use App\Commands\Blog\SanitizeBlogContent;
use App\Commands\Blog\Section\CreateSection;
use App\Processes\Process;

final class CreateSectionProcess extends Process
{
    protected array $tasks = [
        SanitizeBlogContent::class,
        CreateSection::class,
        RefreshBlogView::class,
    ];
}
