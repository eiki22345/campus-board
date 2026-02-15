<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\MajorCategory;
use App\Models\Board;
use Illuminate\View\View;


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

    public function edit(): View
    {

        $user = Auth::user();

        $user_university = $user->university;

        $major_categories = MajorCategory::all();

        $university_boards = Board::where('university_id', $user->university_id)->with('majorCategory')->get();

        $common_boards = Board::whereNull('university_id')->get();

        return view('users.edit', compact('user', 'user_university', 'major_categories', 'university_boards', 'common_boards'));
    }


    public function update(ProfileUpdateRequest $request)
    {

        $request->user()->fill($request->only(['nickname']));
        $request->user()->save();


        return redirect()->route('users.edit')->with('status', 'profile-updated');
    }


    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
