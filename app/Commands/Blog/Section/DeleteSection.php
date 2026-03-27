<?php

declare(strict_types=1);

namespace App\Commands\Blog\Section;

use App\Models\BlogSection;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class DeleteSection
{
    public function handle(object $payload, Closure $next): mixed
    {
        $blogSection = BlogSection::query()->find($payload->id);

        throw_if(! $blogSection, ModelNotFoundException::class, 'Blog Section not found.');

        $payload->productId = $blogSection->product_id;

        $blogSection->delete();

        return $next($payload);
    }
}
