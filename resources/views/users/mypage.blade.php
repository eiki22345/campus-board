@extends('layouts.app')

@section('content')
<main>
  <div class="container py-4">
    <div class="row">
      <div class="col-md-4">
        <div class="card mb-3">
          <div class="card-body text-center">
            <h5 class="card-title">{{ Auth::user()->nickname }}</h5>
            <p class="text-muted">{{ Auth::user()->university->name ?? '所属なし' }}</p>
            <a href="{{ route('users.edit') }}" class="btn btn-outline-secondary btn-sm">プロフィール編集</a>
          </div>
        </div>
        <div>
          <a href="{{ route('dashboard') }}" class="prevent-double-click">
            <small class="py-2">← トップページへ戻る</small>
          </a>
        </div>

        <div class="list-group">
          <a href="#notice" class="list-group-item list-group-item-action">🔔 お知らせ</a>
          <a href="#bookmark" class="list-group-item list-group-item-action">📌 購読中のスレッド</a>
          <a href="#history" class="list-group-item list-group-item-action">🕒 閲覧履歴</a>
          <a href="#self-post" class="list-group-item list-group-item-action">📝 自分の投稿</a>
        </div>
      </div>

      <div class="col-md-8">
        <div class="card">
          <div id="notice" class="card-header bg-white">
            🔔 運営からのお知らせ
          </div>
          <div class="list-group list-group-flush">
            @forelse(Auth::user()->notifications as $notification)
            <div class="list-group-item {{ $notification->read_at ? '' : 'bg-light' }}">
              <div>
                <p class="mb-1">{{ $notification->data['message'] ?? '通知があります' }}</p>
                <div class="d-flex justify-content-end">
                  <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
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
          <div id="bookmark" class="card-header bg-white">
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
              <div>
                <h6 class="mb-1 text-primary fw-bold">{{ $thread->title }}</h6>
                <div class="d-flex justify-content-end">
                  <small class="text-muted">{{ $thread->updated_at->diffForHumans() }}</small>
                </div>
              </div>
              <small class="text-muted">
                レス数: {{ $thread->posts_count ?? $thread->posts->count() }}
              </small>
            </a>
            @endforeach
            @endif
          </div>
        </div>

        <div class="card mt-4">
          <div id="history" class="card-header bg-white">
            🕒 最近見たスレッド
          </div>
          <div class="list-group list-group-flush">
            @if(Auth::user()->viewedThreads->isEmpty())
            <div class="list-group-item text-center text-muted py-4">
              閲覧履歴はありません
            </div>
            @else
            @foreach(Auth::user()->viewedThreads->take(5) as $thread)
            <a href="{{ route('threads.show', [$thread->board_id, $thread->id]) }}" class="list-group-item list-group-item-action">
              <div>
                <h6 class="mb-1 text-dark">{{ $thread->title }}</h6>
                <div class="d-flex justify-content-end">
                  <small class="text-muted">
                    {{ \Carbon\Carbon::parse($thread->pivot->accessed_at)->diffForHumans() }}
                  </small>
                </div>
              </div>
            </a>
            @endforeach
            @endif
          </div>
        </div>
        <div class="card mt-4">
          <div id="self-post" class="card-header bg-white">
            📝 最近の自分の投稿
          </div>
          <div class="list-group list-group-flush">
            @if(Auth::user()->posts->isEmpty())
            <div class="list-group-item text-center text-muted py-4">
              まだ投稿はありません
            </div>
            @else
            @foreach(Auth::user()->posts()->with('thread')->latest()->take(5)->get() as $post)
            @if($post->thread)
            <a href="{{ route('threads.show', [$post->thread->board_id, $post->thread->id]) }}" class="list-group-item list-group-item-action">
              <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                <small class="text-primary fw-bold">
                  {{ Str::limit($post->thread->title, 30) }}
                </small>
                <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
              </div>
              <p class="mb-0 text-dark" style="font-size: 0.95rem;">
                {{ Str::limit($post->content, 60) }}
              </p>
            </a>
            @endif
            @endforeach
            @endif
          </div>

          @if(Auth::user()->posts()->count() > 5)
          <div class="card-footer bg-white text-center">
            <small class="text-muted">最新の5件を表示しています</small>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</main>

@endsection