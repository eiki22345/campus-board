<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Board;
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

        return View('boards.index', compact('user_university', 'university_boards', 'common_boards'));
    }
}
