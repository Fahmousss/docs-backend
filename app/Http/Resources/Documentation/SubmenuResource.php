<?php

declare(strict_types=1);

namespace App\Http\Resources\Documentation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $id
 * @property string $submenu_name
 * @property string $content
 * @property int    $sort_order
 */
final class SubmenuResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => (string) $this->id,
            'submenu_name' => (string) $this->submenu_name,
            'content'      => (string) $this->content,
            'sort_order'   => (int) $this->sort_order,
        ];
    }
}
