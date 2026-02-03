<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = ['name', 'sort_order'];

    public function universities()
    {
        return $this->hasMany(University::class);
    }
}
