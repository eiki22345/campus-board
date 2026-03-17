<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\NgWord;
use App\Models\University;
use App\Models\User;
use Closure;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

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
            'nickname' => [
                'required',
                'string',
                'max:8',
                function (string $attribute, mixed $value, Closure $fail) {
                    $ngWords = NgWord::pluck('word')->toArray();
                    if (in_array($value, $ngWords)) {
                        $fail('不適切なニックネームは使用できません。');
                    }
                },
            ],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
            'agree' => ['accepted'],
        ]);

        $university_domain = substr(strrchr($request->email, "@"), 1);

        $parts = explode('.', $university_domain);
        $alternative = [];

        while (count($parts) >= 2) {
            $alternative[] = implode('.', $parts);
            array_shift($parts);
        }

        $university = University::whereIn('email_domain', $alternative)->first();

        if (!$university) {
            return back()->with('error_message', '入力された大学のメールアドレスは、現在対応していません。')->withInput();
        }

        $user = new User();
        $user->nickname = $request->nickname;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->university_id = $university->id;
        $user->role = 0;
        $user->save();

        Auth::login($user);

        try {
            event(new Registered($user));
        } catch (\Exception $e) {
            return redirect(route('verification.notice', absolute: false))
                ->with('status', 'network-error');
        }

        return redirect(route('verification.notice', absolute: false));
    }
}
