<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Board;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BoardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $user_university = $user->university;

        $university_boards = Board::where('university_id', $user->university_id)->get();

        $common_boards = Board::whereNull('university_id')->get();

        $threads = Thread::with('board')->whereHas('board', function ($query) use ($user) {
            $query->where(function ($q) use ($user) {
                $q->where('university_id', $user->university_id)->OrwhereNull('university_id');
            });
        })->withCount('posts')->orderBy('created_at', 'desc')->paginate(10);

        return View('boards.index', compact('user_university', 'university_boards', 'common_boards', 'threads'));
    }
}
