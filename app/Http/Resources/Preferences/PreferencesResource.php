<?php

declare(strict_types=1);

namespace App\Http\Resources\Preferences;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transforms a single flat row from product_preferences_view into a typed structure.
 *
 * @property string      $item_id
 * @property string      $product_id
 * @property string      $section_id
 * @property string      $section_name
 * @property int         $section_sort
 * @property string      $item_name
 * @property null|string $content
 * @property int         $item_sort
 */
final class PreferencesResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'item_id'      => (string) $this->item_id,
            'product_id'   => (string) $this->product_id,
            'section_id'   => (string) $this->section_id,
            'section_name' => (string) $this->section_name,
            'section_sort' => (int) $this->section_sort,
            'item_name'    => (string) $this->item_name,
            'content'      => $this->content ? (string) $this->content : null,
            'item_sort'    => (int) $this->item_sort,
        ];
    }
}
