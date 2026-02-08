@extends('layouts.app')

@section('content')

<header>
  <x-headers.header :major_categories='$major_categories' :user_university=' $user_university' :university_boards='$university_boards' :common_boards='$common_boards' />

  <x-headers.header-search :action="route('threads.show', [$board->id,$thread->id])" placeholder="🔍️トピック内で話題を検索！" />
</header>
<main>
  <div class=" col-md-8 mx-auto post-background-color">

    <x-link-button.support-link-button :sort='$sort' />

    <div class="post-top ms-2 mt-3">
      <div class="mb-3">
        <a href="{{ route('dashboard') }}">
          TOP
        </a>
        >
        <a href="{{ route('threads.index', $board->id ) }}">
          {{ $board->name }}
        </a>
        >コメント
      </div>
      <div>
        <p class="post-information mb-0">作成者:{{ $thread->user->nickname }}・{{ $thread->created_at->diffForHumans() }}</p>
        <span class="fs-6 fw-bold">{{ $thread->title }}</span>
        <p class="text-content mt-2">>>{{ $thread->content }}</p>
        <hr>
      </div>
    </div>

    @if($posts->isEmpty())
    <div class="text-center py-5 text-gray-500">
      @if(!empty($keyword))
      <p>キーワード「{{ $keyword }}」を含むコメントは見つかりませんでした。</p>
      <a href="{{ route('threads.show', [$board->id, $thread->id]) }}" class="text-blue-500 hover:underline">
        全てのコメントを表示する
      </a>
      @else
      <p>まだ投稿がありません。一番乗りでコメントしましょう！</p>
      @endif
    </div>
    @endif

    @foreach ($posts as $post)

    <div class="mx-auto post-card" x-data="{ replyOpen: false }">
      <div class="d-flex justify-content-between align-items-center">
        <div class="post-information text-break">
          No.{{ $post->post_number}}
          投稿者:{{ $post->user->nickname }}
        </div>
        <div class="post-information">
          {{ $post->created_at->diffForHumans() }}

          <button type="button" class="btn btn-sm btn-link text-danger text-decoration-none post-information" data-bs-toggle="modal" data-bs-target="#reportModal-post-{{ $post->id }}">
            通報
          </button>

          @push('modals')<x-modals.report-modal :target_id="$post->id" type="post" />@endpush
        </div>
      </div>

      <div class="pt-1">
        <span class="text-content">{{ $post->content }}</span>
      </div>
      <div class="d-flex justify-content-between">
        <div class="d-flex align-items-center mb-1">
          <button type="button" class="create-thread-btn" data-bs-toggle="modal" data-bs-target="#replyModal-{{ $post->id }}">
            <span class="action-button">返信</span>
          </button>
          <x-modals.create-mentions :thread="$thread" :post="$post" />
        </div>
        <div class="d-flex align-items-center me-3">
          {{-- ボタン --}}
          {{-- class="post-like-btn" をJSで探します --}}
          {{-- data-url に通信先のURLを埋め込みます --}}
          <div class="action-button">
            <button type="button"
              class="btn p-0 border-0 post-like-btn"
              data-url="{{ route('posts.like', $post) }}">

              {{-- アイコン：PHPで初期状態を判定 --}}
              @if($post->isLikedBy(Auth::user()))
              {{-- いいね済み：赤色の塗りつぶし --}}
              <i class="fa-solid fa-heart fa-lg text-danger post-like-icon"></i>
              @else
              {{-- 未いいね：灰色の枠線 --}}
              <i class="fa-regular fa-heart fa-lg text-secondary post-like-icon"></i>
              @endif
            </button>
            {{-- いいね数 --}}
            <span class="ms-2 post-like-count">
              {{ $post->likes()->count() }}
            </span>
          </div>
          <div>
            <button type="button" class="btn p-0 border-0 d-flex align-items-center ms-2" @click="replyOpen = !replyOpen">
              <div class="ms-2 action-button">{{ $post->replies_count }} 件の返信を表示</div>
              {{-- オプション：開閉がわかる矢印アイコン（あれば） --}}
              <i class="fa-solid fa-chevron-down ms-1 text-secondary small"
                :style="replyOpen ? ' transform: rotate(180deg)' : ''"
                style=" transition: 0.3s;"></i>
            </button>
          </div>



          @if (Auth::id() === $post->user_id)
          <button type="button" class="create-thread-btn" data-bs-toggle="modal" data-bs-target="#delete-post-modal-{{ $post->id }}">
            <img src="{{ asset('img/delete.png') }}" class="delete-img">
          </button>
          <x-modals.delete-modal name="投稿" action="route('posts.destroy', $post)" :post='$post' />
          @endif
        </div>

      </div>
      <div x-show="replyOpen" x-transition class="mt-3 ps-3 border-start" style="display: none; border-color: #ddd;">

        @if($post->replies->isNotEmpty())
        @foreach ($post->replies as $reply)
        <div class="bg-light p-2 mb-2 rounded">
          <div class="d-flex justify-content-between small text-muted">
            <span class="post-information">{{ $reply->user->nickname }}</span>
            <span class="post-information">{{ $reply->created_at->diffForHumans() }}</span>
          </div>
          <div class="mt-1">
            <span class="text-content">{!! nl2br(e($reply->content)) !!}</span>
          </div>
        </div>
        @endforeach
        @else
        <div class="text-muted small p-2">返信はまだありません</div>
        @endif

        {{-- ここに「この投稿に返信するボタン」を置くのも一般的です --}}

      </div>
    </div>

    @endforeach


    <div class="thread-col">
      <div class="create-thread">
        <button type="button" class="create-thread-btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
          <img src="{{ asset('img/create.png') }}" class="create-img">
        </button>
      </div>
      <x-modals.create-post :thread='$thread' />
    </div>
  </div>

  <script>
    // ... 既存のスレッド用コードがあるはず ...

    // ★ここから追記：レス（Post）用のいいね処理
    document.querySelectorAll('.post-like-btn').forEach(button => {
      button.addEventListener('click', async function() {
        // 連打防止
        this.disabled = true;

        const url = this.dataset.url;
        const icon = this.querySelector('.post-like-icon');
        const countSpan = this.nextElementSibling; // 隣にある数字のspan

        try {
          const response = await fetch(url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
          });

          if (!response.ok) throw new Error('Network error');

          const data = await response.json();

          // ■ サーバーからの返却値に合わせて数字を更新
          // Controllerで 'count' として返している場合
          countSpan.textContent = data.count;

          // ■ ハートのデザイン切り替え
          // Controllerで 'liked' (true/false) として返している場合
          if (data.liked) {
            // いいね！された時
            icon.classList.remove('fa-regular', 'text-secondary'); // 枠線を消す
            icon.classList.add('fa-solid', 'text-danger'); // 塗りつぶし赤を追加
          } else {
            // 解除された時
            icon.classList.remove('fa-solid', 'text-danger'); // 塗りつぶし赤を消す
            icon.classList.add('fa-regular', 'text-secondary'); // 枠線に戻す
          }

        } catch (error) {
          console.error('Error:', error);
          alert('いいねの処理に失敗しました');
        } finally {
          // ボタンを再度押せるように復活
          this.disabled = false;
        }
      });
    });
  </script>
</main>