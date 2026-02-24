<?php

declare(strict_types=1);

namespace App\Commands\Shared;

use Closure;
use HTMLPurifier;
use HTMLPurifier_Config;

final readonly class SanitizeRichText
{
    public function __construct(
        private string $field = 'content'
    ) {}

    /**
     * Handle the command.
     */
    public function handle(object $payload, Closure $next): mixed
    {
        $fieldName = $this->field;

        if (! isset($payload->{$fieldName}) || $payload->{$fieldName} === null) {
            return $next($payload);
        }

        $config = HTMLPurifier_Config::createDefault();

        // Allowed tags
        $config->set('HTML.Allowed', 'p,br,strong,em,b,i,u,s,ul,ol,li,h1,h2,h3,h4,h5,h6,a[href|target|rel],img[src|alt|width|height],blockquote,code,pre,table,thead,tbody,tr,th[colspan|rowspan],td[colspan|rowspan],span,div');

        // Allow 'class' on all tags
        $config->set('HTML.AllowedAttributes', '*.class');

        $purifier = new HTMLPurifier($config);

        $payload->{$fieldName} = $purifier->purify($payload->{$fieldName});

        return $next($payload);
    }
}
