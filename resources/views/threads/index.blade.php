@extends('layouts.app')

@section('content')

<header>
  <x-headers.header :major_categories='$major_categories' :user_university=' $user_university' :university_boards='$university_boards' :common_boards='$common_boards' />

  <x-headers.header-search :action="route('threads.index', $board->id)" placeholder="🔍️トピック内で話題を検索！" :keyword='$keyword' />
</header>
<main>

  <div class=" col-md-8 mx-auto">

    <x-link-button.support-link-button :sort='$sort' />

    <div class="mb-2 ms-2 mt-2">
      <a href="{{ route('dashboard') }}" class="prevent-double-click">
        TOP
      </a>
      >{{ $board->name }}
    </div>

    @if($threads->isEmpty())
    <div class="text-center py-5">
      @if(!empty($keyword))
      <p class="text-gray-500 mb-2">キーワード「{{ $keyword }}」に一致するスレッドは見つかりませんでした。</p>
      <div class="space-y-2">
        <a href="{{ route('threads.index', $board) }}" class="text-blue-500 hover:underline block prevent-double-click">
          全てのスレッドを表示する
        </a>
      </div>
      @else
      <p class="text-gray-500 mb-4">この掲示板にはまだトピックがありません。</p>
      <p class="text-gray-400">最初の話題を作ってみましょう！</p>
      @endif
    </div>
    @endif

    @foreach ($threads as $thread)

    <div class="mx-auto thread-card">
      <a href="{{ route('threads.show',[$board->id, $thread->id]) }}" class="thread-link prevent-double-click">
        <div class="d-flex justify-content-between">
          <div class="post-information">
            {{ $thread->user->nickname }}

            ・{{ $thread->created_at->diffForHumans() }}
          </div>
          @if ( $thread->board->university_id === $user_university->id )
          <div class="d-flex justify-content-end post-information">
            {{ $user_university->name }}専用
          </div>
          @endif
        </div>



        <div>
          <span class="fw-bold fs-6">{{ $thread->title }}</span>
        </div>

        <div class="content-preview">
          {{ $thread->content ?? '' }}
        </div>
      </a>

      <div>
        <span class="genre-{{ $board->majorcategory->id }}"> {{ Str::after($board->name, '/') }}</span>
      </div>
      <div class="d-flex justify-content-end">
        {{-- head内にFontAwesomeがある前提 --}}

        <div class="d-flex align-items-center action-button">
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
          <span class="ms-1 like-count">
            {{ $thread->likes()->count() }}
          </span>
        </div>
        <div class="d-flex align-items-center action-button ms-2">
          <img src="{{ asset('img/comment.png') }}" class="comment-img">
          <div class="ms-1"> {{ $thread->posts_count }} </div>
        </div>

        <button type="button" class="btn btn-sm btn-link text-danger text-decoration-none"
          data-bs-toggle="modal"
          data-bs-target="#reportModal-thread-{{ $thread->id }}">
          <span class="action-button">⚠️通報</span>
        </button>

        @push('modals')
        <x-modals.report-modal :target_id="$thread->id" type="thread" />
        @endpush

        @if (Auth::id() === $thread->user_id)
        <button type="button" class="create-thread-btn" data-bs-toggle="modal" data-bs-target="#delete-thread-modal-{{ $thread->id }}">
          <img src="{{ asset('img/delete.png') }}" class="delete-img me-3">
        </button>
        @push('modals')
        <x-modals.delete-modal name="トピック" :action="route('threads.destroy',[$board->id,$thread->id,])" :post="$thread" type="thread" />
        @endpush
        @endif
      </div>
    </div>
    @endforeach

  </div>



  <div class="col-md-7 mx-auto thread-col">
    <div class="create-thread">
      <button type="button" class="create-thread-btn" data-bs-toggle="modal" data-bs-target="#createThreadModal">
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