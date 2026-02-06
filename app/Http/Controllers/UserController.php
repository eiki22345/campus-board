<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\MajorCategory;
use App\Models\Board;

class UserController extends Controller
{

    public function mypage()
    {
        $user = Auth::user();

        $user_university = $user->university;

        $major_categories = MajorCategory::all();

        $university_boards = Board::where('university_id', $user->university_id)->with('majorCategory')->get();

        $common_boards = Board::whereNull('university_id')->get();


        return view('users.mypage', compact('user', 'user_university', 'major_categories', 'university_boards', 'common_boards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
