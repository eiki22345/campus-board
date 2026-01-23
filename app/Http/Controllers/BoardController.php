<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Board;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BoardController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $user = auth()->user();

        $user_university = $user->university;

        $university_boards = Board::where('university_id', $user->university_id)->get();

        $common_boards = Board::whereNull('university_id')->get();

        $query = Thread::with('board')->whereHas('board', function ($query) use ($user) {
            $query->where(function ($q) use ($user) {
                $q->where('university_id', $user->university_id)
                    ->orWhereNull('university_id');
            });
        });

        if (!empty($keyword)) {
            $keywords = preg_split('/[\s]+/', mb_convert_kana($keyword, 's'), -1, PREG_SPLIT_NO_EMPTY);

            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $word) {
                    $q->where(function ($subQ) use ($word) {
                        $subQ->where('title', 'LIKE', "%{$word}%")
                            ->orWhere('content', 'LIKE', "%{$word}%");
                    });
                }
            });
        }

        $threads = $query->withCount('posts')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends(['keyword' => $keyword]);

        return View('boards.index', compact('user_university', 'university_boards', 'common_boards', 'threads', 'keyword'));
    }
}
