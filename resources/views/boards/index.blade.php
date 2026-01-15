@extends('layouts.app')

@section('content')

<header>
    <x-headers.header :user_university='$user_university' :university_boards='$university_boards' :common_boards='$common_boards' />

    <x-headers.header-search />
</header>
<main>
    <div class="col-md-8 mx-auto">
        <div class="board-thread-bg img-fluid">
            <div class="row g-0 pt-3">
                <div class="col-4 board-index-icon">
                    <a href="#" class="board-index-icon-link">
                        <img src="{{ asset('img/fire.png') }}" class="board-index-icon-img">
                        <p class="text-center fw-bold mb-0">人気</p>
                    </a>
                </div>
                <div class="col-4 board-index-icon">
                    <a class="board-index-icon-link" data-bs-toggle="offcanvas" href="#offcanvasTop" role="button" aria-controls="offcanvasTop">
                        <img src="{{ asset('img/university.png') }}" class="board-index-icon-img">
                        <p class="text-center fw-bold mb-0">ジャンル一覧</p>
                    </a>
                </div>
                <div class="col-4 board-index-icon">
                    <a href="#" class="board-index-icon-link">
                        <img src="{{ asset('img/new.png') }}" class="board-index-icon-img">
                        <p class="text-center fw-bold mb-0">新着</p>
                    </a>
                </div>
            </div>
            <div class="row g-0">
                <div class="col-4 board-index-icon py-1">
                    <a href="#" class="board-index-icon-link">
                        <img src="{{ asset('img/usage.png') }}" class="board-index-icon-img">
                        <p class="text-center fw-bold mb-0">使い方</p>
                    </a>
                </div>
                <div class="col-4 board-index-icon py-1">
                    <a href="#" class="board-index-icon-link">
                        <img src="{{ asset('img/history.png') }}" class="board-index-icon-img">
                        <p class="text-center fw-bold mb-0">履歴</p>
                    </a>
                </div>
                <div class="col-4 board-index-icon py-1">
                    <a href="#" class="board-index-icon-link">
                        <img src="{{ asset('img/system.png') }}" class="board-index-icon-img">
                        <p class="text-center fw-bold mb-0">設定</p>
                    </a>
                </div>
            </div>
            <div class="mt-3">
                @foreach ($threads as $thread)

                <div class="mx-auto thread-card">
                    <a href="{{ route('threads.show',[$thread->board->id, $thread->id]) }}" class="thread-link">
                        <div class="d-flex justify-content-between">
                            <div>
                                投稿者:{{ $thread->user->nickname }}
                                @if ($thread->board->university_id == $thread->user->university_id)
                                ({{ $user_university->name }}専用)
                                @endif
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
        </div>
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