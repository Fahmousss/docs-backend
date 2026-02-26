<?php

declare(strict_types=1);

namespace App\Http\Resources\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * @mixin Product
 */
final class ProductResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => (string) $this->id,
            'name'       => (string) $this->name,
            'created_at' => $this->created_at instanceof Carbon ? $this->created_at->toIso8601String() : ($this->created_at !== null ? (string) $this->created_at : null),
            'updated_at' => $this->updated_at instanceof Carbon ? $this->updated_at->toIso8601String() : ($this->updated_at !== null ? (string) $this->updated_at : null),
        ];
    }
}
