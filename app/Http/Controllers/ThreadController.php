<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Thread;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    use AuthorizesRequests;

    public function index(Board $board, Request $request)
    {
        $sort = $request->input('sort', 'new');
        $keyword = $request->input('keyword');

        $this->authorize('view', $board);

        $user = auth()->user();

        $user_university = $user->university;

        $university_boards = Board::where('university_id', $user->university_id)->get();

        $common_boards = Board::whereNull('university_id')->get();

        $query = $board->threads()->with(['user:id,nickname'])->withCount(['posts', 'likes']);

        if (!empty($keyword)) {

            $keywords = preg_split('/[\s]/', mb_convert_kana($keyword, 's'), -1, PREG_SPLIT_NO_EMPTY);

            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $word) {
                    $q->where(function ($subQ) use ($word) {
                        $subQ->where('title', 'LIKE', "%{$word}%")->orWhere('content', 'LIKE', "%{$word}%");
                    });
                }
            });
        }

        if ($sort === 'popular') {
            $query->orderBy('likes_count', 'desc')
                ->orderBy('created_at', 'desc');
        } else {
            $query->latest();
        }

        $threads = $query->paginate(20)->appends(['keyword' => $keyword, 'sort' => $sort]);

        return view('threads.index', compact('user_university', 'university_boards', 'common_boards', 'board', 'threads', 'keyword', 'sort'));
    }

    public function store(Request $request, Board $board)
    {

        $validated = $request->validate([
            'title' => 'required|max:50',
            'content' => 'required|max:500',
        ]);

        $user = Auth::user();

        if ($board->university_id !== null && $board->university_id !== $user->university_id) {
            abort(403, '他大学の掲示板に書き込めません。');
        }

        $thread = new Thread();
        $thread->title = $validated['title'];
        $thread->content = $validated['content'];
        $thread->board_id = $board->id;
        $thread->user_id = Auth::id();
        $thread->ip_address = $request->ip();


        $thread->save();

        return redirect()->route('threads.show', [$board->id, $thread->id])->with('message', 'スレッドを作成しました。');
    }

    public function show(Board $board, Thread $thread, Request $request)
    {
        $sort = $request->input('sort', 'new');
        $keyword = $request->input('keyword');

        $user = auth()->user();

        $user_university = $user->university;

        $university_boards = Board::where('university_id', $user->university_id)->get();

        $common_boards = Board::whereNull('university_id')->get();

        $query = $thread->posts()
            ->doesntHave('parents')
            ->with('user:id,nickname', 'replies.user', 'likes')
            ->withCount(['likes', 'replies']);

        $keyword = $request->input('keyword');

        if (!empty($keyword)) {
            $keywords = preg_split('/[\s]+/', mb_convert_kana($keyword, 's'), -1, PREG_SPLIT_NO_EMPTY);

            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $word) {
                    $q->where('content', 'LIKE', "%{$word}%");
                }
            });
        }

        if ($sort === 'popular') {
            $query->orderBy('likes_count', 'desc')
                ->orderBy('post_number', 'asc');
        } else {
            $query->orderBy('post_number', 'asc');
        }

        $posts = $query->paginate(20)
            ->appends(['keyword' => $keyword, 'sort' => $sort]);

        return view('threads.show', compact('user_university', 'university_boards', 'common_boards', 'board', 'thread', 'posts', 'keyword', 'sort'));
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'この投稿を削除する権限がありません。');
        }

        $post->delete();

        return back()->with('message', '投稿を削除しました。');
    }

    // メソッドを追加
    public function toggleLike(Thread $thread)
    {
        $user = Auth::user();

        // toggleメソッド：ユーザーIDが中間テーブルにあれば削除、なければ追加する
        $thread->likes()->toggle($user->id);

        // 最新のいいね数を取得
        $likesCount = $thread->likes()->count();

        // 現在の状態（いいねしているかどうか）
        $isLiked = $thread->likes()->where('user_id', $user->id)->exists();

        // JSON形式で結果を返す（画面遷移させないため）
        return response()->json([
            'likes_count' => $likesCount,
            'is_liked' => $isLiked,
        ]);
    }
}
