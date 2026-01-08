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

        $user = Auth::user();

        $board = $thread->board;

        if ($parent_post != null && $parent_post->thread_id !== $thread->id) {
            abort(400, '不正な返信先です。');
        }


        if ($board->university_id !== null && $board->university_id !== $user->university_id) {
            abort(403, '他大学の掲示板に書き込むことはできません。');
        }

        DB::transaction(function () use ($request, $validated, $thread, $user, $parent_post) {
            $max_post_number = Post::where('thread_id', $thread->id)->lockForUpdate()->max('post_number');

            $next_post_number = ($max_post_number ?? 0) + 1;


            $new_post = new Post();
            $new_post->thread_id = $thread->id;
            $new_post->user_id = $user->id;
            $new_post->post_number = $next_post_number;
            $new_post->content = $validated['content'];
            $new_post->ip_address = $request->ip();
            $new_post->save();

            if ($parent_post) {
                $new_post->parents()->attach($parent_post->id);
            }
        });







        return redirect()->route('threads.show', [$board->id, $thread->id])->with('message', 'メッセージを作成しました。');
    }

    public function toggleLike(Post $post)
    {
        $user = Auth::user();

        // 自分の大学の板かなどのチェックが必要ならここに追加
        // if ($post->thread->board->university_id !== ... ) abort(403);

        // ★魔法のメソッド toggle
        // 既にいいねしてれば「解除」、してなければ「登録」を自動判別
        $post->likes()->toggle($user->id);

        return response()->json([
            'status' => 'success',
            'liked'  => $post->isLikedBy($user), // 最新の状態 (true/false)
            'count'  => $post->likes()->count(), // 最新の件数
        ]);
    }
}
