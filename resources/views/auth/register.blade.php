@extends('layouts.app')

@section('content')
<header>

</header>

<div class="auth-background">
  <header>
    <div class="auth-header">
      <div class="ms-3 mt-3 text-white">
        <h1>STUDENT BBS</h1>
        <h1>CAMPUS BOARD</h1>
      </div>
      <div>
        <img src="{{ asset('img/mascot.png') }}" class="header-img mt-4 me-3">
      </div>

  </header>
  <main>
    <div class="container">
      <div class="row d-flex justify-content-center py-4 px-2">
        <div class="col-md-5 auth-form">
          <h1 class="text-center auth-form-heading mt-5">
            アカウントを作成
          </h1>
          <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="form-group mt-4">
              <label for="nickname" class="col-form-label ps-2">ニックネーム</label>
              <input id="nickname" type="text" class="form-control @error('nickname') is-invalid  @enderror hokkai-board-register-input py-2" name="nickname" value="{{ old('nickname') }}" required autocomplete="nickname" autofocus placeholder="ニックネーム(※本名は避けてください)">

              @error('nickname')
              <div class="invalid-feedback ps-2">
                {{ $message }}
              </div>
              @enderror
            </div>

            <div class="form-group">
              <label for="email" class="col-form-label ps-2 pb-0">大学のメールアドレス</label>
              <input id="email" type="text" class="form-control @error('email') is-invalid  @enderror hokkai-board-register-input py-2 mt-2" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="対応大学のメールアドレス限定">

              @error('email')
              <div class="invalid-feedback ps-2">
                {{ $message }}
              </div>
              @enderror
              @if( session( 'error_message') )
              <div class="auth-form-error ps-2">
                <span>{{session( 'error_message' ) }}</span>
              </div>
              @endif
            </div>

            <div class="form-group ps-2 ">
              <a href="#" class=" index-added-university">
                <p class="mb-0 mt-1">対応大学一覧はこちら</p>
              </a>
            </div>

            <div class="form-group ps-2">
              <a href="#" class="add-university-request ">
                <p class="mb-0">リストにない大学の追加リクエストはこちら</p>
              </a>
            </div>

            <div class="form-group">
              <label for="password" class="col-form-label ps-2">パスワード</label>
              <input id="password" type="password" class="form-control @error('password') is-invalid  @enderror hokkai-board-register-input py-2" name="password" required autocomplete="new-password" autofocus placeholder="パスワード">

              @error('password')
              <div class="invalid-feedback ps-2">
                {{ $message }}
              </div>
              @enderror
            </div>

            <div class="form-group">
              <label for="password-confirm" class="col-form-label ps-2">パスワード確認</label>
              <input id="password-confirm" type="password" class="form-control hokkai-board-register-input py-2" name="password_confirmation" required autocomplete="new-password" autofocus placeholder="パスワード確認">
            </div>

            <div class="form-group mt-1 ps-2">
              <input type="checkbox" name="agree" class="ms-2">
              <label for="agree" class="col-form-label"><span>利用規約</span>に同意する</label>
            </div>
            <div class="d-flex justify-content-center">
              <button class="register-link d-flex justify-content-center mb-5 px-5 w-100">
                <h3 class="py-2 text-center register-link-text text-white">新規登録</h3>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
  <div
    @endsection