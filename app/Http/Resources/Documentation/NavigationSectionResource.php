<?php

declare(strict_types=1);

namespace App\Http\Resources\Documentation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string                                   $id
 * @property string                                   $section_name
 * @property int                                      $sort_order
 * @property \Illuminate\Database\Eloquent\Collection $menus
 */
final class NavigationSectionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => (string) $this->id,
            'section_name' => (string) $this->section_name,
            'sort_order'   => (int) $this->sort_order,
            'menus'        => NavigationMenuResource::collection($this->menus),
        ];
    }
}
