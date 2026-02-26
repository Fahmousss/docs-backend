<?php

declare(strict_types=1);

namespace App\DTOs\Documentation;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * @phpstan-type SectionArray array{id:string,name:string,sortOrder:int,menus:array<int, array{id:string,name:string,sortOrder:int,submenus:array<int, array{id:string,name:string,content?:string|null,sortOrder:int}>>>}
 */
final class DocumentationPayload extends Data
{
    /**
     * @param DataCollection<int, SectionData> $sections
     */
    public function __construct(
        public readonly string $productId,
        #[DataCollectionOf(SectionData::class)]
        public readonly DataCollection $sections,
    ) {}
}
