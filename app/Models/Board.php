<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Board extends Model
{

    protected $fillable = [
        'name',
        'major_category_id',
        'university_id',
    ];

    public function majorCategory(): BelongsTo
    {
        return $this->belongsTo(MajorCategory::class);
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }
}
