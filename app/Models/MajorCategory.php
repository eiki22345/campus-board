<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MajorCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    public function boards(): HasMany
    {
        return $this->hasMany(Board::class);
    }
}
