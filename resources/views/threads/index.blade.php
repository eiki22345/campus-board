@extends('layouts.app')

@section('content')

<div class="container-fluid px-0">
  <div class="row g-0 two-col-layout">
    <div class="col-12 col-md-9 mx-auto posts-col">

      <header>
        <x-headers.header :major_categories='$major_categories' :user_university=' $user_university' :university_boards='$university_boards' :common_boards='$common_boards' />

        <x-headers.header-search :action="route('threads.index', $board->id)" placeholder="🔍️トピック内で話題を検索！" :keyword='$keyword' />
      </header>

      <x-link-button.support-link-button :sort='$sort' />

      <div class="mb-2 ms-4 mt-2">
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
              {{ $thread->user->nickname ?? '退会済みユーザー'  }}
              ・{{ $thread->created_at->diffForHumans() }}
            </div>
            <div class="d-flex flex-column align-items-end gap-1">
              @if ( $thread->board->university_id === $user_university->id )
              <div class="post-information">
                {{ $user_university->name }}専用
              </div>
              @endif
              <div>
                <span class="genre-{{ $board->majorcategory->id }}"> {{ Str::after($board->name, '/') }}</span>
              </div>
            </div>
          </div>


          <div class="mb-2 pt-2">
            <span class="fw-bold fs-5">{{ $thread->title }}</span>
          </div>

          <div class="content-preview fs-6 mb-2">
            {{ $thread->content ?? '' }}
          </div>
        </a>

        <div class="d-flex justify-content-between align-items-center">
          <form action="{{ route('threads.subscribe', $thread) }}" method="POST">
            @csrf
            @if(Auth::user()->subscribedThreads->contains($thread))
            <button type="submit" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1 py-0 px-1">
              <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-bookmark-check-fill" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M2 15.5V2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.74.439L8 13.069l-5.26 2.87A.5.5 0 0 1 2 15.5zm8.854-9.646a.5.5 0 0 0-.708-.708L7.5 7.793 6.354 6.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3-3z" />
              </svg>
              <span class="post-information">購読中</span>
            </button>
            @else
            <button type="submit" class="btn btn-primary btn-sm d-flex align-items-center gap-1 py-0 px-1">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmark-plus" viewBox="0 0 16 16">
                <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5V2zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1H4z" />
                <path d="M8 4a.5.5 0 0 1 .5.5V6H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V7H6a.5.5 0 0 1 0-1h1.5V4.5A.5.5 0 0 1 8 4z" />
              </svg>
              <span class="post-information text-white">購読する</span>
            </button>
            @endif
          </form>
          <div class="d-flex align-items-center gap-1">
            <div class="d-flex align-items-center action-button">
              <button class="btn p-0 border-0 like-btn"
                data-url="{{ route('threads.like', $thread) }}">
                <i class="fa-heart fa-lg text-danger {{ $thread->isLikedByAuthUser() ? 'fa-solid' : 'fa-regular' }} like-icon"></i>
              </button>
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
          </div><!-- /right buttons -->
        </div><!-- /action-bar -->
      </div><!-- /thread-card -->
      @endforeach

      <div class="thread-col">
        <div class="create-thread">
          <button type="button" class="create-thread-btn" data-bs-toggle="modal" data-bs-target="#createThreadModal">
            <img src="{{ asset('img/create.png') }}" class="create-img">
          </button>
        </div>
        <x-modals.create-thread :board='$board' />
      </div>

    </div><!-- /posts-col -->
  </div><!-- /row -->
</div><!-- /container-fluid -->

<script>
  document.querySelectorAll('.like-btn').forEach(button => {
    button.addEventListener('click', async function() {
      this.disabled = true;

      const url = this.dataset.url;
      const icon = this.querySelector('.like-icon');
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

        countSpan.textContent = data.likes_count;

        if (data.is_liked) {
          icon.classList.remove('fa-regular');
          icon.classList.add('fa-solid');
        } else {
          icon.classList.remove('fa-solid');
          icon.classList.add('fa-regular');
        }

      } catch (error) {
        alert('いいねの処理に失敗しました');
      } finally {
        this.disabled = false;
      }
    });
  });
</script>

@endsection