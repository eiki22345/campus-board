<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Thread;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Rules\NoInappropriateWords;
use App\Events\PostCreated;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



class PostController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Thread $thread, ?Post $post = null)
    {


        $validated = $request->validate([
            'content' => ['required', 'string', 'max:1000', new NoInappropriateWords],
        ]);

        $parent_post = $post;

        $user = Auth::user();

        $board = $thread->board;

        if ($parent_post != null) {
            if ($parent_post->thread_id !== $thread->id) {
                abort(400, '不正な返信先です。');
            }

            if ($parent_post->trashed()) {
                abort(400, '削除された投稿には返信できません。');
            }

            if ($parent_post->parents()->exists()) {
                abort(400, '返信投稿にはさらに返信できません。');
            }
        }

        $this->authorize('create', [Post::class, $board]);

        try {
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

                try {
                    PostCreated::dispatch($new_post);
                } catch (\Exception $broadcastException) {
                    Log::warning('Failed to broadcast PostCreated event: ' . $broadcastException->getMessage());
                }
            });

            return back()->with('message', '投稿を作成しました。');
        } catch (\Exception $e) {
            Log::error($e);
            return back()->withInput()->with('error', '投稿に失敗しました。時間をおいて再度お試しください。');
        }
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);

        $post->load('thread');

        $html = view('components.posts.post-item', [
            'post' => $post,
            'thread' => $post->thread,
        ])->render();

        return response()->json(['html' => $html]);
    }

    public function toggleLike(Post $post)
    {
        $this->authorize('view', $post);

        $user = Auth::user();


        $post->likes()->toggle($user->id);

        return response()->json([
            'status' => 'success',
            'liked'  => $post->isLikedBy($user),
            'count'  => $post->likes()->count(),
        ]);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $thread = $post->thread;
        $board = $thread->board;

        try {
            $post->delete();

            return redirect()->route('threads.show', [$board->id, $thread->id])
                ->with('message', '投稿を削除しました。');
        } catch (\Exception $e) {
            Log::error($e);
            return back()->with('error', '削除に失敗しました。時間をおいて再度お試しください。');
        }
    }
}
