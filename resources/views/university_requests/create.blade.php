@extends('layouts.app')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8">

      {{-- カードデザイン --}}
      <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body p-5">

          {{-- ヘッダー部分 --}}
          <div class="text-center mb-5">
            <h2 class="h4 fw-bold text-primary mb-2">大学追加リクエスト</h2>
            <p class="text-secondary small">リストにない大学の追加依頼を受け付けています</p>
          </div>

          {{-- 完了メッセージ --}}
          @if (session('status'))
          <div class="alert alert-success rounded-3 d-flex align-items-center mb-4" role="alert">
            <div class="flex-grow-1">
              {{ session('status') }}
            </div>
          </div>
          @endif

          <form method="POST" action="{{ route('university-request.store') }}">
            @csrf

            {{-- 大学名 --}}
            <div class="mb-4">
              <label for="name" class="form-label fw-bold text-secondary small">
                {{ __('大学の正式名称') }} <span class="text-danger">*</span>
              </label>
              <input id="name" type="text"
                class="form-control form-control-lg bg-light border-0 {{ $errors->has('name') ? 'is-invalid' : '' }}"
                name="name" value="{{ old('name') }}" required autofocus
                placeholder="例: 北海学園大学" />
              @error('name')
              <div class="invalid-feedback fw-bold">
                {{ $message }}
              </div>
              @enderror
            </div>

            {{-- メールアドレス --}}
            <div class="mb-4">
              <label for="email" class="form-label fw-bold text-secondary small">
                {{ __('あなたの大学メールアドレス') }} <span class="text-danger">*</span>
              </label>
              <input id="email" type="email"
                class="form-control form-control-lg bg-light border-0 {{ $errors->has('email') ? 'is-invalid' : '' }}"
                name="email" value="{{ old('email') }}" required
                placeholder="例: tarou@hgu.jp" />
              <div class="form-text text-muted mt-2">
                ※ドメイン（@以降）の確認に使用します。
              </div>
              @error('email')
              <div class="invalid-feedback fw-bold">
                {{ $message }}
              </div>
              @enderror
            </div>

            {{-- URL --}}
            <div class="mb-5">
              <label for="verification_url" class="form-label fw-bold text-secondary small">
                {{ __('大学の実在確認用URL') }} <span class="text-danger">*</span>
              </label>
              <input id="verification_url" type="url"
                class="form-control form-control-lg bg-light border-0 {{ $errors->has('verification_url') ? 'is-invalid' : '' }}"
                name="verification_url" value="{{ old('verification_url') }}" required
                placeholder="例: https://www.hgu.jp/" />
              @error('verification_url')
              <div class="invalid-feedback fw-bold">
                {{ $message }}
              </div>
              @enderror
            </div>

            {{-- ボタンエリア --}}
            <div class="d-flex justify-content-between align-items-center pt-2">
              <a class="text-decoration-none text-secondary fw-bold small" href="{{ route('register') }}">
                &larr; {{ __('登録画面に戻る') }}
              </a>

              <button type="submit" class="btn btn-dark btn-lg px-5 rounded-pill fw-bold shadow-sm">
                {{ __('リクエスト送信') }}
              </button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection