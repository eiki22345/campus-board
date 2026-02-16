<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use illuminate\Database\Eloquent\Relations\BelongsTo;

class BrowsingHistory extends Model
{
    // user_idはMass Assignment攻撃防止のため除外
    // コントローラー側で指定することで不整な入力の防止
    protected $fillable = ['thread_id', 'accessed_at'];

    protected $casts = [
        'accessed_at' => 'datetime',
    ];

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }
}
