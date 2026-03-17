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
        <a href="{{ route('dashboard') }}" class="prevent-double-click">
          TOP
        </a>
        >
        <a href="{{ route('threads.index', $board->id ) }}" class="prevent-double-click">
          {{ $board->name }}
        </a>
        >コメント
      </div>
      <div>
        <p class="post-information mb-0">作成者:{{ $thread->user->nickname ?? '退会済みユーザー' }}・{{ $thread->created_at->diffForHumans() }}</p>
        <span class="fs-6 fw-bold">{{ $thread->title }}</span>
        <p class="text-content mt-2">>>{{ $thread->content }}</p>
        <hr>
      </div>
    </div>

    @if($posts->isEmpty())
    <div class="text-center py-5 text-gray-500">
      @if(!empty($keyword))
      <p>キーワード「{{ $keyword }}」を含むコメントは見つかりませんでした。</p>
      <a href="{{ route('threads.show', [$board->id, $thread->id]) }}" class="text-blue-500 hover:underline prevent-double-click">
        全てのコメントを表示する
      </a>
      @else
      <p>まだコメントがありません。一番乗りでコメントしましょう！</p>
      @endif
    </div>
    @endif

    <div id="posts-container" data-thread-id="{{ $thread->id }}">
      @foreach ($posts as $post)
      <x-posts.post-item :post="$post" :thread="$thread" />
      @endforeach
    </div>

    <div class="thread-col">
      <div class="create-thread">
        <button type="button" class="create-thread-btn" data-bs-toggle="modal" data-bs-target="#createPostModal">
          <img src="{{ asset('img/create.png') }}" class="create-img">
        </button>
      </div>
      <x-modals.create-post :thread='$thread' />
    </div>
  </div>

  <script>
    document.querySelectorAll('.post-like-btn').forEach(button => {
      button.addEventListener('click', async function() {
        this.disabled = true;

        const url = this.dataset.url;
        const icon = this.querySelector('.post-like-icon');
        const countSpan = this.nextElementSibling;

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

          countSpan.textContent = data.count;

          if (data.liked) {
            icon.classList.remove('fa-regular', 'text-secondary');
            icon.classList.add('fa-solid', 'text-danger');
          } else {
            icon.classList.remove('fa-solid', 'text-danger');
            icon.classList.add('fa-regular', 'text-secondary');
          }

        } catch (error) {
          alert('いいねの処理に失敗しました');
        } finally {
          this.disabled = false;
        }
      });
    });
  </script>

  <script>
    const container = document.getElementById('posts-container');
    const thread_id = container.dataset.threadId;

    setTimeout(() => {
      if (window.Echo) {
        window.Echo.private(`thread.${thread_id}`)
          .listen('.post.created', (e) => {
            if (container && window.DOMPurify) {
              const cleanHTML = window.DOMPurify.sanitize(e.post_html);
              container.insertAdjacentHTML('beforeend', cleanHTML);
            }
          });
      }
    }, 1000);
  </script>
</main>
@endsection