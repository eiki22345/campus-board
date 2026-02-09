@extends('layouts.app')

@section('content')
<header>

</header>

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
                    <h2 class="text-center auto-form-heading mt-5">メールをご確認ください</h2>

                    <p class="text-center auth-verify-text mt-4">
                        大学のメールアドレスにログイン用リンクを送信しました。<br>
                        メール本文内のURLをクリックすると新規登録が完了します。
                    </p>

                    <div class="d-flex justify-content-center">
                        <img src="{{ asset('img/verify-email.png') }}" class="auth-verify-img">
                    </div>

                    <p class="text-center auth-verify-text mt-4">

                        届かない場合は、迷惑メールフォルダをご確認いただくか、<br>
                        入力ミスがないかご確認ください。
                    </p>

                    <form action="{{ route('verification.send') }}" method="POST">
                        @csrf
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="register-link d-flex justify-content-center mb-5 px-5">
                                <h3 class="py-2 text-center login-link-text text-white">メールを再送信</h3>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection