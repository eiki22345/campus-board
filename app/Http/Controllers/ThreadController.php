<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\MajorCategory;
use App\Models\Thread;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Rules\NoInappropriateWords;

class ThreadController extends Controller
{
    use AuthorizesRequests;

    public function index(Board $board, Request $request)
    {
        $sort = $request->input('sort', 'new');
        $keyword = $request->input('keyword');
        $major_categories = MajorCategory::all();

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

        return view('threads.index', compact('major_categories', 'user_university', 'university_boards', 'common_boards', 'board', 'threads', 'keyword', 'sort'));
    }

    public function store(Request $request, Board $board)
    {

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:50', new NoInappropriateWords],
            'content' =>  ['required', 'string', 'max:1000', new NoInappropriateWords],
        ]);

        $user = Auth::user();

        if ($board->university_id !== null && $board->university_id !== $user->university_id) {
            abort(403, '他大学の掲示板に書き込めません。');
        }

        try {

            $new_thread = DB::transaction(function () use ($request, $validated, $board, $user) {
                $thread = new Thread();
                $thread->title = $validated['title'];
                $thread->content = $validated['content'];
                $thread->board_id = $board->id;
                $thread->user_id = $user->id;
                $thread->ip_address = $request->ip();
                $thread->save();

                return $thread;
            });

            return redirect()->route('threads.show', [$board->id, $new_thread->id])
                ->with('message', 'スレッドを作成しました。');
        } catch (\Exception $e) {
            Log::error($e);
            return back()->withInput()->with('error', 'スレッドの作成に失敗しました。');
        }
    }

    public function show(Board $board, Thread $thread, Request $request)
    {
        $sort = $request->input('sort', 'new');
        $keyword = $request->input('keyword');
        $major_categories = MajorCategory::all();

        $user = auth()->user();

        if ($user) {
            \App\Models\BrowsingHistory::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'thread_id' => $thread->id,
                ],
                [
                    'accessed_at' => now(),
                ]
            );
        }

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

        return view('threads.show', compact('major_categories', 'user_university', 'university_boards', 'common_boards', 'board', 'thread', 'posts', 'keyword', 'sort'));
    }

    public function destroy(Board $board, Thread $thread)
    {
        if ($board->id !== $thread->board_id) {
            abort(404);
        }

        if ($thread->user_id !== Auth::id()) {
            abort(403, '削除権限がありません。');
        }

        try {
            DB::transaction(function () use ($thread) {
                $thread->posts()->delete();
                $thread->delete();
            });

            return redirect()->route('threads.index', $board->id)
                ->with('message', 'スレッドを削除しました。');
        } catch (\Exception $e) {
            Log::error($e);
            return back()->with('error', 'スレッドの削除に失敗しました。時間をおいて再度お試しください。');
        }
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
