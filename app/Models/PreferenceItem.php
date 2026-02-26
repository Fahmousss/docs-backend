<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class PreferenceItem extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'id',
        'preference_section_id',
        'item_name',
        'content',
        'sort_order',
    ];

    /**
     * @return BelongsTo<PreferenceSection, $this>
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(PreferenceSection::class, 'preference_section_id');
    }
}
