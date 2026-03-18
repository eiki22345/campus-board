<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Notifications\AccountDeletionRequested;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
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
        $user->deletion_requested_at = now();
        $user->save();

        $cancelUrl = URL::temporarySignedRoute(
            'account-deletion.cancel',
            now()->addDays(7),
            ['user' => $user->id]
        );

        $user->notify(new AccountDeletionRequested($cancelUrl));

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'deletion-scheduled');
    }

    public function cancelDeletion(Request $request, User $user)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'このリンクは無効または期限切れです。');
        }

        if (is_null($user->deletion_requested_at)) {
            return redirect('/')->with('status', 'deletion-already-cancelled');
        }

        $user->deletion_requested_at = null;
        $user->save();

        return redirect('/login')->with('status', 'deletion-cancelled');
    }
}
