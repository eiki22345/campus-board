@extends('layouts.app')

@section('content')

<header>
  <x-headers.header :major_categories='$major_categories' :user_university=' $user_university' :university_boards='$university_boards' :common_boards='$common_boards' />
</header>
<main>
  <div class="container py-4">
    <div class="row">
      <div class="col-md-4">
        <div class="card mb-3">
          <div class="card-body text-center">
            <h5 class="card-title">{{ Auth::user()->nickname }}</h5>
            <p class="text-muted">{{ Auth::user()->university->name ?? '所属なし' }}</p>
            <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-sm">プロフィール編集</a>
          </div>
        </div>

        <div class="list-group">
          <a href="#" class="list-group-item list-group-item-action active">🔔 お知らせ</a>
          <a href="#" class="list-group-item list-group-item-action">📌 購読中のスレッド</a>
          <a href="#" class="list-group-item list-group-item-action">🕒 閲覧履歴</a>
          <a href="#" class="list-group-item list-group-item-action">📝 自分の投稿</a>
        </div>
      </div>

      <div class="col-md-8">
        <div class="card">
          <div class="card-header bg-white">
            🔔 運営からのお知らせ
          </div>
          <div class="list-group list-group-flush">
            @forelse(Auth::user()->notifications as $notification)
            <div class="list-group-item {{ $notification->read_at ? '' : 'bg-light' }}">
              <div class="d-flex w-100 justify-content-between">
                <p class="mb-1">{{ $notification->data['message'] ?? '通知があります' }}</p>
                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
              </div>

              @if(is_null($notification->read_at))
              <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="btn btn-sm btn-link text-decoration-none p-0">
                  既読にする
                </button>
              </form>
              @endif
            </div>
            @empty
            <div class="list-group-item text-center text-muted py-4">
              まだお知らせはありません
            </div>
            @endforelse
          </div>
        </div>

        <div class="card mt-4">
          <div class="card-header bg-white">
            📌 購読中のスレッド
          </div>
          <div class="list-group list-group-flush">
            @if(Auth::user()->subscribedThreads->isEmpty())
            <div class="list-group-item text-center text-muted py-4">
              購読中のスレッドはありません
            </div>
            @else
            @foreach(Auth::user()->subscribedThreads as $thread)
            <a href="{{ route('threads.show', [$thread->board_id, $thread->id]) }}" class="list-group-item list-group-item-action">
              <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1 text-primary fw-bold">{{ $thread->title }}</h6>
                <small class="text-muted">{{ $thread->updated_at->diffForHumans() }}</small>
              </div>
              <small class="text-muted">
                レス数: {{ $thread->posts_count ?? $thread->posts->count() }}
              </small>
            </a>
            @endforeach
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

@endsection