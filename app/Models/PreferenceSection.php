<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class PreferenceSection extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'id',
        'product_id',
        'name',
        'sort_order',
    ];

    /**
     * @return HasMany<PreferenceItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(PreferenceItem::class)->orderBy('sort_order');
    }
}
