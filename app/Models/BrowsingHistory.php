<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use illuminate\Database\Eloquent\Relations\BelongsTo;

class BrowsingHistory extends Model
{
    protected $fillable = ['user_id', 'thread_id', 'accessed_at'];

    protected $casts = [
        'accessed_at' => 'datetime',
    ];

    public $timestanps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }
}
