<?php

declare(strict_types=1);

namespace App\Processes\Blog;

use App\Commands\Blog\BulkUpsertBlogSections;
use App\Commands\Blog\RefreshBlogView;
use App\Commands\Blog\SanitizeBlogContent;
use App\Commands\Shared\AssignMissingUuids;
use App\Processes\Process;

final class UpdateBlogProcess extends Process
{
    /**
     * @var array<int, class-string>
     */
    protected array $tasks = [
        AssignMissingUuids::class,
        SanitizeBlogContent::class,
        BulkUpsertBlogSections::class,
        RefreshBlogView::class,
    ];
}
