@extends('layouts.app')

@section('content')
<div class="auth-background">
    <header>
        <div class="auth-header">
            <div class="ms-3 mt-3 text-white">
                <h1>STUDENT BBS</h1>
                <h1>CAMPUS BOARD</h1>
            </div>
            <div>
                <img src="{{ asset('img/mascot.png') }}" class="header-img mt-4 me-3 mb-2">
            </div>
        </div>
    </header>
    <main>
        <div class="container">
            <div class="row d-flex justify-content-center py-4 px-2">
                <div class="col-md-5 auth-form">
                    <h1 class="text-center login-heading mt-5">
                        ログイン
                    </h1>
                    <form action="{{ route('login') }}" method="POST" x-data="{ submitting: false }" @submit="submitting = true">
                        @csrf

                        <div class="form-group mt-4">
                            <input id="email" type="email" class="form-control @error('email') is-invalid  @enderror hokkai-board-login-input py-2" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="メールアドレスを入力">

                            @error('email')
                            <div class="invalid-feedback ps-2">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group mt-4">
                            <input id="password" type="password" class="form-control @error('password') is-invalid  @enderror hokkai-board-login-input py-2" name="password" required autocomplete="current-password" placeholder="パスワードを入力">
                            @error('password')
                            <div class="invalid-feedback ps-2">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group mt-2 ms-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember_me" {{ old('remember') ? 'checked' : ''}}>
                                <label class="form-check-label" for="remember">
                                    次回から自動ログインする
                                </label>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <a href="{{ route('password.request') }}" class="login-forgot-password ms-2 prevent-double-click">
                                パスワードをお忘れですか？(こちらをクリック)
                            </a>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="mt-2 w-100 login-btn py-1" :disabled="submitting">
                                <span x-show="!submitting">
                                    <h4 class="login-label">ログイン</h4>
                                </span>

                                {{-- 送信中の表示（Bootstrapのスピナーを使用） --}}
                                <span x-show="submitting">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                        <h4 class="login-label">ログイン中...</h4>
                                    </div>
                                </span>
                            </button>
                        </div>
                    </form>

                    <hr class="mt-4">
                    <h5 class="text-center mb-4">アカウントをお持ちではないですか？</h5>
                    <a href="{{ route('register') }}" class=" login-link d-flex justify-content-center mb-5 prevent-double-click">
                        <h3 class="py-2 text-center login-link-text text-dark">新規登録</h3>
                    </a>

                </div>
            </div>
        </div>
    </main>
</div>
@endsection