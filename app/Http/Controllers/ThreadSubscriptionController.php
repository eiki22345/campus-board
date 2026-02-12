<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThreadSubscriptionController extends Controller
{

    public function toggle(Thread $thread)
    {
        $user = Auth::user();
        if ($user->subscribedThreads()->where('thread_id', $thread->id)->exists()) {
            $user->subscribedThreads()->detach($thread->id);
            $message = 'スレッドの購読を解除しました。';
        } else {
            $user->subscribedThreads()->attach($thread->id);
            $message = 'スレッドを購読しました！マイページから確認できます。';
        }

        return back()->with('status', $message);
    }
}
