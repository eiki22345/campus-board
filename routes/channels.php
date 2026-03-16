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
    return is_null($thread->board->university_id) || $thread->board->university_id === $user->university_id;
});
