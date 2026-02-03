<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Board;
use App\Models\MajorCategory;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BoardController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'new');
        $keyword = $request->input('keyword');

        $user = auth()->user();

        $user_university = $user->university;

        $major_categories = MajorCategory::all();

        $university_boards = Board::where('university_id', $user->university_id)->with('majorCategory')->get();

        $common_boards = Board::whereNull('university_id')->get();

        $query = Thread::with(['board', 'user:id,nickname'])->withCount(['posts', 'likes'])->whereHas('board', function ($query) use ($user) {
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

        if ($sort === 'popular') {
            $query->orderBy('likes_count', 'desc')
                ->orderBy('created_at', 'desc');
        } else {
            $query->latest();
        }

        $threads = $query->paginate(20)
            ->appends(['keyword' => $keyword, 'sort' => $sort]);

        return View('boards.index', compact('major_categories', 'user_university', 'university_boards', 'common_boards', 'threads', 'keyword', 'sort'));
    }
}
