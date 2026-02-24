<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Product extends Model
{
    protected $fillable = [
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
     * @return HasMany<Showcase, $this>
     */
    public function showcases(): HasMany
    {
        return $this->hasMany(Showcase::class);
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
