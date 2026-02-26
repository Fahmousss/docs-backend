<?php

declare(strict_types=1);

namespace App\Http\Resources\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transforms a single flat row from product_blog_view into a typed structure.
 *
 * @property string       $section_id
 * @property string       $product_id
 * @property string       $title
 * @property string       $publish_date
 * @property null|string  $description
 * @property null|string  $content
 * @property null|string  $hero_image_url
 * @property array|string $creators
 * @property int          $sort_order
 */
final class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $creators = is_string($this->creators) ? json_decode($this->creators, true, 512, JSON_THROW_ON_ERROR) : $this->creators;

        return [
            'id'           => $this->section_id,
            'productId'    => $this->product_id,
            'title'        => $this->title,
            'publishDate'  => $this->publish_date,
            'description'  => $this->description,
            'content'      => $this->content,
            'heroImageUrl' => $this->hero_image_url,
            'creators'     => $creators,
            'sortOrder'    => (int) $this->sort_order,
        ];
    }
}
