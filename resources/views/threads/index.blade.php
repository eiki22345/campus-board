@extends('layouts.app')

@section('content')

<header>
  <x-headers.header :user_university='$user_university' :university_boards='$university_boards' :common_boards='$common_boards' />

  <x-headers.header-search />
</header>
<main>

  <div class="col-md-7 mx-auto col-background-color">
    <div class="mt-1 mb-3">
      <a href="{{ route('dashboard') }}">
        TOP
      </a>
      >{{ $board->name }}
    </div>
    @foreach ($threads as $thread)

    <div class="mx-auto w-90 thread-card">
      <a href="{{ route('threads.show',[$board->id, $thread->id]) }}" class="thread-link">
        <div class="d-flex justify-content-between">
          <div>
            投稿者:{{ $thread->user->nickname }}
          </div>
          <div>
            {{ $thread->created_at}}
          </div>
        </div>

        <div class="py-2">
          <h5 class="fw-bold">{{ $thread->title }}</h5>
        </div>
      </a>
      <div class="d-flex justify-content-end">
        {{-- head内にFontAwesomeがある前提 --}}

        <div class="d-flex align-items-center">
          {{-- いいねボタン --}}
          {{-- data-thread-id: どのスレッドか --}}
          {{-- data-liked: 今自分がいいねしてるか (true/false) --}}
          {{-- data-url という名前で、完全なURL（http://.../hokkai-board/...）を埋め込みます --}}
          <button class="btn p-0 border-0 like-btn"
            data-url="{{ route('threads.like', $thread) }}">

            {{-- 自分がいいねしてたら solid(塗りつぶし)、してなければ regular(枠線) --}}
            <i class="fa-heart fa-lg text-danger {{ $thread->isLikedByAuthUser() ? 'fa-solid' : 'fa-regular' }} like-icon"></i>
          </button>

          {{-- いいね数 --}}
          <span class="ms-2 like-count">
            {{ $thread->likes()->count() }}
          </span>
        </div>
        <div class="d-flex align-items-center ms-2">
          <img src="{{ asset('img/comment.png') }}" class="comment-img">
          <div class="ms-2"> {{ $thread->posts_count }} </div>
        </div>
      </div>
    </div>
    @endforeach

  </div>



  <div class="col-md-7 mx-auto thread-col">
    <div class="create-thread">
      <button type="button" class="create-thread-btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        <img src="{{ asset('img/create.png') }}" class="create-img">
      </button>
    </div>
    <x-modals.create-thread :board='$board' />
  </div>
</main>

<script>
  document.querySelectorAll('.like-btn').forEach(button => {
    button.addEventListener('click', async function() {
      // 連打防止（処理中は無効化）
      this.disabled = true;

      const url = this.dataset.url;
      const icon = this.querySelector('.like-icon');
      const countSpan = this.nextElementSibling; // 隣にある数字のspan

      try {
        // サーバーに送信
        const response = await fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            // LaravelでFetchするときのお約束（CSRFトークン）
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          }
        });

        if (!response.ok) throw new Error('Network error');

        const data = await response.json();

        // サーバーからの結果を使って表示を更新
        countSpan.textContent = data.likes_count;

        // ハートの切り替え
        if (data.is_liked) {
          icon.classList.remove('fa-regular');
          icon.classList.add('fa-solid'); // 塗りつぶし
        } else {
          icon.classList.remove('fa-solid');
          icon.classList.add('fa-regular'); // 枠線
        }

      } catch (error) {
        console.error('Error:', error);
        alert('いいねの処理に失敗しました');
      } finally {
        // ボタンを再度押せるようにする
        this.disabled = false;
      }
    });
  });
</script>

@endsection