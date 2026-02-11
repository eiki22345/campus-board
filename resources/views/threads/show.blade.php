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
        <p class="post-information mb-0">作成者:{{ $post->user->nickname ?? '退会済みユーザー' }}・{{ $thread->created_at->diffForHumans() }}</p>
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
      <p>まだ投稿がありません。一番乗りでコメントしましょう！</p>
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

  <script type="module">
    // ▼▼▼ 修正: HTMLの属性からIDを取得する（これならPHPを書かないのでズレない） ▼▼▼
    const container = document.getElementById('posts-container');
    const thread_id = container.dataset.threadId;

    console.log('Script loaded, thread_id:', thread_id);
    console.log('window.Echo:', window.Echo);

    setTimeout(() => {
      console.log('setTimeout executed, window.Echo:', window.Echo);
      if (window.Echo) {
        console.log('Subscribing to channel: thread.' + thread_id);
        window.Echo.channel(`thread.${thread_id}`)
          .listen('.post.created', (e) => {
            console.log('New post received!', e);
            if (container) {
              container.insertAdjacentHTML('beforeend', e.post_html);
            }
          });
      } else {
        console.error('Echo is not defined!');
      }
    }, 1000);
  </script>
</main>
@endsection