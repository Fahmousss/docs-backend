<?php

declare(strict_types=1);

namespace App\Commands\Preferences;

use App\DTOs\Preferences\PreferencesPayload;
use App\Services\HtmlSanitizerService;
use Closure;

final readonly class SanitizePreferencesContent
{
    public function __construct(private HtmlSanitizerService $sanitizer) {}

    public function handle(PreferencesPayload $payload, Closure $next): mixed
    {
        foreach ($payload->sections as $section) {
            foreach ($section->items as $item) {
                $item->content = $this->sanitizer->clean($item->content);
            }
        }

        return $next($payload);
    }
}
