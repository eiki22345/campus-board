<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;

class Login extends BaseLogin
{
    /**
     * ログイン試行時の認証処理
     * ブルートフォース攻撃対策としてレート制限を適用
     */
    public function authenticate(): ?LoginResponse
    {
        // IP アドレスベースでレート制限（60秒間に5回まで）
        $this->checkRateLimit(5);

        return parent::authenticate();
    }

    /**
     * レート制限の実装
     * 
     * @param int $maxAttempts 最大試行回数
     * @throws ValidationException
     */
    protected function checkRateLimit(int $maxAttempts): void
    {
        $key = 'filament-login.' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            
            throw ValidationException::withMessages([
                'data.email' => __('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        // 1分間のレート制限window
        RateLimiter::hit($key, 60);
    }
}
