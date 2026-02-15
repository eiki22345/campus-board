@props(['post', 'thread'])

<div class="mx-auto post-card" x-data="{ replyOpen: false }">
  <div class="d-flex justify-content-between align-items-center">
    <div class="post-information text-break">
      {{ $post->post_number}}
      {{ $post->user->nickname ?? '退会済みユーザー' }}
      ID:{{ $post->hash_id }}
    </div>
    <div class="post-information d-flex align-items-center">
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
  <div class="d-flex justify-content-between mt-2">
    <div class="d-flex align-items-center mb-1">
      <button type="button" class="create-thread-btn" data-bs-toggle="modal" data-bs-target="#replyModal-{{ $post->id }}">
        <span class="action-button">返信</span>
      </button>
      <x-modals.create-mentions :thread="$thread" :post="$post" />
    </div>
    <div class="d-flex align-items-center me-3">
      <div class="action-button">
        <x-buttons.like-button :type="$post" />
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
      <x-modals.delete-modal name="投稿" :action="route('posts.destroy',$post->id)" :post="$post" type="post" />
      @endif
    </div>

  </div>
  <div x-show="replyOpen" x-transition class="mt-3 ps-3 border-start" style="display: none; border-color: #ddd;">

    @if($post->replies->isNotEmpty())
    @foreach ($post->replies as $reply)
    <div class="bg-light p-2 mb-2 rounded">
      <div class="d-flex justify-content-between small text-muted">
        <div>
          {{ $reply->post_number}}
          <span class="post-information">{{ $reply->user->nickname ?? '退会済みユーザー' }}</span>
          ID:{{ $reply->hash_id}}
        </div>
        <div>
          <span class="post-information">{{ $reply->created_at->diffForHumans() }}</span>
          <button type="button" class="btn btn-sm btn-link text-danger text-decoration-none post-information" data-bs-toggle="modal" data-bs-target="#reportModal-post-{{ $reply->id }}">
            通報
          </button>

          @push('modals')<x-modals.report-modal :target_id="$reply->id" type="post" />@endpush
        </div>
      </div>
      <div class="mt-1">
        <span class="text-content">{!! nl2br(e($reply->content)) !!}</span>
      </div>
      <div class="d-flex justify-content-end">
        <div class="d-flex align-items-center me-3">
          {{-- ボタン --}}
          {{-- class="post-like-btn" をJSで探します --}}
          {{-- data-url に通信先のURLを埋め込みます --}}
          <div class="action-button">
            <x-buttons.like-button :type="$reply" />
          </div>
          @if (Auth::id() === $post->user_id)
          <button type="button" class="create-thread-btn" data-bs-toggle="modal" data-bs-target="#delete-reply-modal-{{ $reply->id }}">
            <img src="{{ asset('img/delete.png') }}" class="delete-img me-3">
          </button>
          <x-modals.delete-modal name="投稿" :action="route('posts.destroy',$reply->id)" :post="$reply" type="reply" />
          @endif
        </div>
      </div>
    </div>
    @endforeach
    @else
    <div class="text-muted small p-2">返信はまだありません</div>
    @endif

    {{-- ここに「この投稿に返信するボタン」を置くのも一般的です --}}

  </div>
</div>