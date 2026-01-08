@extends('layouts.app')

@section('content')

<header>
  <x-headers.header :user_university='$user_university' :university_boards='$university_boards' :common_boards='$common_boards' />

  <x-headers.header-search />
</header>
<main>

  <div class="col-md-8 mx-auto post-background-color pb-3">
    <div class="post-top ms-1">
      <div class="mb-3">
        <a href="{{ route('dashboard') }}">
          TOP
        </a>
        >
        <a href="{{ route('threads.index', $board->id ) }}">
          {{ $board->name }}
        </a>
        >チャット
      </div>
      <div>
        <h3>{{ $thread->title }}</h3>
        <span>>>{{ $thread->content }}</span>
        <hr>
      </div>
    </div>

    @foreach ($posts as $post)

    <div class="mx-auto post-card">
      <div class="d-flex justify-content-between">
        <div>
          No.{{ $post->post_number}}
          投稿者:{{ $post->user->nickname }}
        </div>
        <div>
          {{ $post->created_at}}
        </div>
      </div>

      <div class="fw-bold pt-1">
        <p>{{ $post->content }}</p>
      </div>
      <div class="d-flex justify-content-end">
        <div class="d-flex align-items-center me-3">
          {{-- ボタン --}}
          {{-- class="post-like-btn" をJSで探します --}}
          {{-- data-url に通信先のURLを埋め込みます --}}
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

        <div class="d-flex align-items-center ms-2">
          <img src="{{ asset('img/comment.png') }}" class="comment-img">
          <div class="ms-2"> {{ $thread->posts_count }} </div>
        </div>
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