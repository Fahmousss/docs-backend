<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class BlogSection extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'product_id',
        'title',
        'publish_date',
        'description',
        'content',
        'hero_image_url',
        'creators',
        'sort_order',
    ];

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected function casts(): array
    {
        return [
            'publish_date' => 'date',
            'creators'     => 'array',
        ];
    }
}
