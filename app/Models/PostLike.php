<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostLike extends Model
{
    // user_idはMass Assignment攻撃防止のため除外
    // toggle()を使用するため通常は不要だが、将来のcreate()使用に備えて除外
    protected $fillable = ['post_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
