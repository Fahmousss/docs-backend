<?php

declare(strict_types=1);

namespace App\Commands\Blog;

use App\DTOs\Blog\BlogPayload;
use App\Services\HtmlSanitizerService;
use Closure;

final readonly class SanitizeBlogContent
{
    public function __construct(
        private HtmlSanitizerService $sanitizer,
    ) {}

    public function handle(BlogPayload $payload, Closure $next): mixed
    {
        foreach ($payload->sections as $section) {
            if ($section->content !== null) {
                $section->content = $this->sanitizer->clean($section->content);
            }
        }

        return $next($payload);
    }
}
