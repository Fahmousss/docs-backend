<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class ShowcaseItem extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'id',
        'product_id',
        'title',
        'description',
        'media_url',
        'content',
        'sort_order',
    ];
}
