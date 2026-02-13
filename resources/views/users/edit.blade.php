@extends('layouts.app')

@section('content')
<main>
  <header>
    <x-headers.header :major_categories='$major_categories' :user_university=' $user_university' :university_boards='$university_boards' :common_boards='$common_boards' />
  </header>

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8">

        @if (session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          ニックネームを更新しました。
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        {{-- プロフィール（ニックネーム）編集フォーム --}}
        <div class="card mb-4 shadow-sm">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">👤 プロフィール編集</h5>
          </div>
          <div class="card-body">
            <form method="post" action="{{ route('users.update') }}">
              @csrf
              @method('patch')

              <div class="mb-3">
                <label for="nickname" class="form-label">ニックネーム</label>
                <input type="text" name="nickname" id="nickname" class="form-control @error('nickname') is-invalid @enderror" value="{{ old('nickname', $user->nickname) }}" required autofocus>
                @error('nickname')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label class="form-label text-muted">メールアドレス</label>
                <input type="text" class="form-control bg-light" value="{{ $user->email }}" disabled readonly>
                <div class="form-text">大学のメールアドレスは変更できません。</div>
              </div>

              <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">保存する</button>
              </div>
            </form>
          </div>
        </div>

        {{-- パスワード変更フォーム（別のルートへ送信） --}}
        <div class="card mb-4 shadow-sm">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">🔒 パスワード変更</h5>
          </div>
          <div class="card-body">
            {{-- 既存のパスワード更新ルートを使用 --}}
            <form method="post" action="{{ route('password.update') }}">
              @csrf
              @method('put')

              <div class="mb-3">
                <label for="update_password_current_password" class="form-label">現在のパスワード</label>
                <input type="password" name="current_password" id="update_password_current_password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password">
                @error('current_password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label for="update_password_password" class="form-label">新しいパスワード</label>
                <input type="password" name="password" id="update_password_password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                @error('password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label for="update_password_password_confirmation" class="form-label">確認用パスワード</label>
                <input type="password" name="password_confirmation" id="update_password_password_confirmation" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
              </div>

              <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-secondary">パスワードを変更</button>
              </div>

              @if (session('status') === 'password-updated')
              <div class="alert alert-success mt-3 mb-0 py-2">パスワードを変更しました。</div>
              @endif
            </form>
          </div>
        </div>

        {{-- アカウント削除 --}}
        <div class="card border-danger shadow-sm">
          <div class="card-header bg-white py-3 text-danger border-danger">
            <h5 class="mb-0 fw-bold">⚠️ アカウント削除</h5>
          </div>
          <div class="card-body">
            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
              アカウントを削除する
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>

  {{-- 削除確認モーダル --}}
  <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form method="post" action="{{ route('users.destroy') }}" class="modal-content">
        @csrf
        @method('delete')
        <div class="modal-header">
          <h5 class="modal-title text-danger fw-bold">本当に削除しますか？</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>確認のためパスワードを入力してください。</p>
          <input type="password" name="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" placeholder="パスワード" required>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
          <button type="submit" class="btn btn-danger">削除実行</button>
        </div>
      </form>
    </div>
  </div>
</main>
@endsection