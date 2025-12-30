<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use illuminate\Database\Eloquent\Relations\BelongsTo;
use illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'thread_id',
        'user_id',
        'post_number',
        'content',
        'image_path',
        'likes_count',
        'ip_address'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }
}
