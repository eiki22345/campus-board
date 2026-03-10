<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Report;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Thread extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'board_id',
        'title',
        'content',
    ];

    protected static function booted()
    {
        static::deleting(function ($thread) {
            DB::transaction(function () use ($thread) {
                $thread->posts()->get()->each->delete();
                Report::where('thread_id', $thread->id)
                    ->where('status', '!=', 'resolved')
                    ->update(['status' => 'resolved']);
            });
        });
    }

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'thread_likes')->withTimestamps();
    }

    public function isLikedByAuthUser()
    {
        if (!Auth::check()) {
            return false;
        }
        return $this->likes()->where('user_id', Auth::id())->exists();
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
