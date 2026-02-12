<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; // ★論理削除用
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens; // APIを使う場合に備えて残しておく
use Filament\Models\Contracts\HasName;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;


class User extends Authenticatable implements MustVerifyEmail, HasName, FilamentUser
{
    use  HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'nickname',
        'email',
        'password',
        'university_id',
        'role',
        'icon_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // --- リレーション設定 ---

    // ユーザーは1つの大学に所属する
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    // ユーザーはたくさんのスレッドを作成する
    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    // ユーザーはたくさんのレスを投稿する
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    // ユーザーはたくさんのサブスク情報を持つ（履歴用）
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function getFilamentName(): string
    {
        return (string) $this->nickname;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 1;
    }

    public function subscribedThreads()
    {
        return $this->belongsToMany(Thread::class, 'thread_subscriptions', 'user_id', 'thread_id')
            ->withTimestamps()
            ->orderByPivot('created_at', 'desc');
    }

    public function viewedThreads()
    {
        return $this->belongsToMany(Thread::class, 'browsing_histories', 'user_id', 'thread_id')
            ->withPivot('accessed_at')
            ->orderByPivot('accessed_at', 'desc');
    }
}
