<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\University;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nickname' => ['required', 'string', 'max:10'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'agree' => ['required'],
        ]);

        $university_domain = substr(strrchr($request->email, "@"), 1);

        $parts = explode('.', $university_domain);
        $alternative = [];

        while (count($alternative) >= 2) {
            $alternative[] = implode('.', $alternative);
            array_shift($alternative);
        }

        $university = University::whereIn('email_domain', $alternative)->first();

        if (!$university) {
            return back()->with('error_message', '入力された大学のメールアドレスは、現在対応していません。')->withInput();
        }

        $user = User::create([
            'nickname' => $request->nickname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'university_id' => $university->id,
            'role' => 0,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/verify-email');
    }
}
