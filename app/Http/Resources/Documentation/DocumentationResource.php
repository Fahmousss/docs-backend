<?php

declare(strict_types=1);

namespace App\Http\Resources\Documentation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transforms a single flat row from product_docs_view into a typed structure.
 *
 * @property string      $product_id
 * @property string      $section_id
 * @property string      $section_name
 * @property int         $section_sort
 * @property null|string $menu_id
 * @property null|string $menu_name
 * @property null|int    $menu_sort
 * @property null|string $submenu_id
 * @property null|string $submenu_name
 * @property null|string $content
 * @property null|int    $submenu_sort
 */
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
