@extends('layouts.app')

@section('content')


<div class="auth-background">
    <header>
        <div class="auth-header">
            <div class="ms-3 mt-3 text-white">
                <h1>STUDENT BBS</h1>
                <h1>HOKKAI BOARD</h1>
            </div>
            <div>
                <img src="{{ asset('img/mascot.png') }}" class="header-img mt-4 me-3">
            </div>

    </header>
    <main>
        <div class="container">
            <div class="row d-flex justify-content-center py-4 px-2">
                <div class="col-md-5 auth-form">
                    <h2 class="text-center auto-form-heading mt-5">パスワード再設定</h2>

                    <p class="text-center auth-verify-text mt-4">
                        ご登録時のメールアドレスを入力してください。<br>
                        パスワード再発行用のメールをお送りします。
                    </p>

                    <hr>

                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror hokkai-board-forgot-input py-2 mt-5" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="メールアドドレス">
                            @error('email')
                            <div class="invalid-feedback ps-2">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group d-flex justify-content-center my-5">
                            <button class="register-link d-flex justify-content-center mb-5 px-5">
                                <h3 class="py-2 text-center login-link-text text-white">送信</h3>
                            </button>
                        </div>

                    </form>


                </div>
            </div>
        </div>
    </main>
</div>
@endsection