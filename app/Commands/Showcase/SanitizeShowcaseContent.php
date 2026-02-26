<?php

declare(strict_types=1);

namespace App\Commands\Showcase;

use App\DTOs\Showcase\ShowcasePayload;
use App\Services\HtmlSanitizerService;
use Closure;

final readonly class SanitizeShowcaseContent
{
    public function __construct(private HtmlSanitizerService $sanitizer) {}

    public function handle(ShowcasePayload $payload, Closure $next): mixed
    {
        foreach ($payload->items as $item) {
            $item->content = $this->sanitizer->clean($item->content);
        }

        return $next($payload);
    }
}
