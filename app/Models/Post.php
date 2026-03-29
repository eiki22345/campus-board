<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Report;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'thread_id',
        'content',
    ];

    protected static function booted()
    {

        static::deleting(function ($post) {
            DB::transaction(function () use ($post) {
                $post->replies()->get()->each->delete();
                Report::where('post_id', $post->id)
                    ->where('status', '!=', 'resolved')
                    ->update(['status' => 'resolved']);
            });
        });
    }

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

    public function isLikedBy($user)
    {
        if (!$user) return false;

        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function getHashIdAttribute()
    {
        if (!$this->user_id) {
            return 'Guest';
        }

        $secret = config('app.hash_id_secret');
        $hmac = hash_hmac('sha256', (string) $this->user_id, $secret . $this->thread_id);

        return substr(strtoupper($hmac), 0, 8);
    }
}
