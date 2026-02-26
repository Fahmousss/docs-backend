<?php

declare(strict_types=1);

namespace App\Services;

use Stevebauman\Purify\Facades\Purify;

final class HtmlSanitizerService
{
    public function clean(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        return Purify::clean($html);
    }
}
