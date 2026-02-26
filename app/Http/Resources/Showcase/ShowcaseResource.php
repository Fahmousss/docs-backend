<?php

declare(strict_types=1);

namespace App\Http\Resources\Showcase;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transforms a single flat row from product_showcase_view into a typed structure.
 *
 * @property string      $item_id
 * @property string      $product_id
 * @property string      $title
 * @property null|string $description
 * @property null|string $media_url
 * @property null|string $content
 * @property int         $sort_order
 */
final class ShowcaseResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => (string) $this->item_id,
            'product_id'  => (string) $this->product_id,
            'title'       => (string) $this->title,
            'description' => $this->description ? (string) $this->description : null,
            'media_url'   => $this->media_url ? (string) $this->media_url : null,
            'content'     => $this->content ? (string) $this->content : null,
            'sort_order'  => (int) $this->sort_order,
        ];
    }
}
