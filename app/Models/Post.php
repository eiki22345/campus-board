<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use illuminate\Database\Eloquent\Relations\BelongsTo;
use illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function replies()
    {
        return $this->belongsToMany(
            Post::class,
            'post_mentions',
            'parent_post_id',
            'post_id'
        )->withTimestamps();
    }

    public function parents()
    {
        return $this->belongsToMany(
            Post::class,
            'post_mentions',
            'post_id',
            'parent_post_id'
        )->withTimestamps();
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_likes')
            ->withTimestamps();
    }

    // 自分がいいねしているか判定するメソッド（Viewで使います）
    public function isLikedBy($user)
    {
        if (!$user) return false;

        // ログインユーザーのIDが、この投稿の「いいねした人リスト」に含まれているか
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
