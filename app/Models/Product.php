<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    use HasUuids;

    public $incrementing = false;

    /**
     * The primary key type and incrementing behaviour for UUIDs.
     */
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
    ];

    /**
     * @return HasMany<Section, $this>
     */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    /**
     * @return HasMany<ShowcaseItem, $this>
     */
    public function showcaseItems(): HasMany
    {
        return $this->hasMany(ShowcaseItem::class)->orderBy('sort_order');
    }

    /**
     * @return HasMany<PreferenceSection, $this>
     */
    public function preferenceSections(): HasMany
    {
        return $this->hasMany(PreferenceSection::class);
    }

    /**
     * @return HasMany<BlogSection, $this>
     */
    public function blogSections(): HasMany
    {
        return $this->hasMany(BlogSection::class);
    }
}
