<?php

declare(strict_types=1);

namespace App\Http\Resources\Documentation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string                                   $id
 * @property string                                   $menu_name
 * @property int                                      $sort_order
 * @property \Illuminate\Database\Eloquent\Collection $submenus
 */
final class NavigationMenuResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => (string) $this->id,
            'menu_name'  => (string) $this->menu_name,
            'sort_order' => (int) $this->sort_order,
            // 'submenus'   => NavigationSubmenuResource::collection($this->submenus),
        ];
    }
}
