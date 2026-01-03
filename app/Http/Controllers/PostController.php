<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Thread;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request, Thread $thread, ?Post $post = null)
    {


        $validated = $request->validate([
            'content' => 'required|max:500',
        ]);

        $parent_post = $post;

        if ($parent_post->thread_id !== $thread->id) {
            abort(400, '不正な返信先です。');
        }

        $user = Auth::user();

        $board = $thread->board;

        if ($board->university_id !== null && $board->university_id !== $user->university_id) {
            abort(403, '他大学の掲示板に書き込むことはできません。');
        }

        DB::transaction(function () use ($request, $validated, $thread, $user, $parent_post) {
            $max_post_number = Post::where('thread_id', $thread->id)->lockForUpdate()->max('post_number');

            $next_post_number = ($max_post_number ?? 0) + 1;


            $post = new Post();
            $post->thread_id = $thread->id;
            $post->user_id = $user->id;
            $post->post_number = $next_post_number;
            $post->content = $validated['content'];
            $post->ip_address = $request->ip();
            $post->save();
        });







        return redirect()->route('threads.show', [$board->id, $thread->id])->with('message', 'メッセージを作成しました。');
    }
}
