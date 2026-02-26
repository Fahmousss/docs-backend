<?php

declare(strict_types=1);

namespace App\Http\Resources\Documentation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class DocumentationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product_id'   => (string) $this->product_id,
            'section_id'   => (string) $this->section_id,
            'section_name' => (string) $this->section_name,
            'section_sort' => (int) $this->section_sort,
            'menu_id'      => $this->menu_id !== null ? (string) $this->menu_id : null,
            'menu_name'    => $this->menu_name !== null ? (string) $this->menu_name : null,
            'menu_sort'    => $this->menu_sort !== null ? (int) $this->menu_sort : null,
            'submenu_id'   => $this->submenu_id !== null ? (string) $this->submenu_id : null,
            'submenu_name' => $this->submenu_name !== null ? (string) $this->submenu_name : null,
            'content'      => $this->content,
            'submenu_sort' => $this->submenu_sort !== null ? (int) $this->submenu_sort : null,
        ];
    }
}
