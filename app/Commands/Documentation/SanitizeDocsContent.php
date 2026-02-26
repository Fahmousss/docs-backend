<?php

declare(strict_types=1);

namespace App\Commands\Documentation;

use App\DTOs\Documentation\DocumentationPayload;
use App\Services\HtmlSanitizerService;
use Closure;

final readonly class SanitizeDocsContent
{
    public function __construct(private HtmlSanitizerService $sanitizer) {}

    public function handle(DocumentationPayload $payload, Closure $next): mixed
    {
        foreach ($payload->sections as $section) {
            foreach ($section->menus as $menu) {
                foreach ($menu->submenus as $submenu) {
                    $submenu->content = $this->sanitizer->clean($submenu->content);
                }
            }
        }

        return $next($payload);
    }
}
