<?php

declare(strict_types=1);
// app/Http/Resources/Documentation/ProductDocumentationResource.php

namespace App\Http\Resources\Documentation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ProductDocumentationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->product_id,
            'name'        => $this->product_name,
            'sections'    => $this->parseCommaSeparated($this->sections),
            'preferences' => [
                'sections' => $this->parseCommaSeparated($this->preference_sections),
                'items'    => $this->parseCommaSeparated($this->preference_items),
            ],
            'blogs'     => $this->parseCommaSeparated($this->blogs),
            'showcases' => $this->parseCommaSeparated($this->showcases),
        ];
    }

    private function parseCommaSeparated(?string $value): array
    {
        if (blank($value)) {
            return [];
        }

        return array_map(trim(...), explode(',', $value));
    }
}
