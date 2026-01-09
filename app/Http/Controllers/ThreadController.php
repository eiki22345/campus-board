<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    use AuthorizesRequests;

    public function index(Board $board)
    {

        $user = auth()->user();

        $user_university = $user->university;

        $university_boards = Board::where('university_id', $user->university_id)->get();

        $common_boards = Board::whereNull('university_id')->get();

        $this->authorize('view', $board);

        $threads = $board->threads()->with(['user:id,nickname'])->withCount('posts')->latest()->paginate(20);

        return view('threads.index', compact('user_university', 'university_boards', 'common_boards', 'board', 'threads', 'board',));
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

    public function show(Board $board, Thread $thread)
    {

        $user = auth()->user();

        $user_university = $user->university;

        $university_boards = Board::where('university_id', $user->university_id)->get();

        $common_boards = Board::whereNull('university_id')->get();

        $posts = $thread->posts()->doesntHave('parents')->with('user:id,nickname', 'replies.user', 'likes')->withCount(['likes', 'replies'])->orderby('post_number', 'asc')->paginate(20);

        return view('threads.show', compact('user_university', 'university_boards', 'common_boards', 'board', 'thread', 'posts'));
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
