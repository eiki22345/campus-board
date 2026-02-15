<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Thread;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('thread.{threadId}', function ($user, $threadId) {
    $thread = Thread::find($threadId);
    if (!$thread) {
        return false;
    }
    // 共通掲示板（university_id が null）または自分の大学の掲示板のみアクセス可能
    return is_null($thread->board->university_id) || $thread->board->university_id === $user->university_id;
});
