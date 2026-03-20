@extends('layouts.app')

@section('content')

<div>
    <div class="row g-0 two-col-layout">
        <div class="col-12 col-md-9 mx-auto posts-col">

            <header>
                <x-headers.header :major_categories='$major_categories' :user_university=' $user_university' :university_boards='$university_boards' :common_boards='$common_boards' :common_boards='$common_boards' />

                <x-headers.header-search :action="route('dashboard')" placeholder="🔍️気になる話題を検索しよう！" :keyword='$keyword' />
            </header>

            <x-link-button.support-link-button :sort='$sort' />

            @if($threads->isEmpty())
            <div class="text-center py-10 shadow-sm">
                @if(!empty($keyword))
                <div class="mb-4">
                    <i class="fas fa-search text-4xl text-gray-300 mb-3"></i>
                    <p class="text-lg text-gray-600 font-bold">「{{ $keyword }}」は見つかりませんでした</p>
                </div>
                <p class="text-gray-500 text-sm mb-4">
                    キーワードを変えて検索するか、<br>
                    カテゴリから探してみてください。
                </p>
                <a href="{{ route('dashboard') }}" class="inline-block px-6 py-2 bg-gray-100 text-gray-600 rounded-full hover:bg-gray-200 transition prevent-double-click">
                    トップページに戻る
                </a>
                @else
                <p class="text-gray-500">現在、表示できるトピックがありません。<br>トピックを作成してください。</p>
                @endif
            </div>
            @endif

            @if ($keyword && $threads->isNotEmpty())
            <div class="ms-4 mt-2">
                <h4>{{$keyword}}で検索中</h4>
            </div>
            @endif

            <div>
                @foreach ($threads as $thread)

                <div class="mx-auto thread-card">
                    <a href="{{ route('threads.show',[$thread->board->id, $thread->id]) }}" class="thread-link prevent-double-click">
                        <div class="d-flex justify-content-between">
                            <div class="post-information">
                                {{ $thread->user->nickname ?? '退会済みユーザー' }}

                                ・{{ $thread->created_at->diffForHumans() }}
                            </div>
                            <div class="d-flex align-items-end gap-1">
                                @if ( $thread->board->university_id === $user_university->id )
                                <div class="post-information">
                                    {{ $user_university->name }}専用
                                </div>
                                @endif
                                <div>
                                    <span class="genre-{{ $thread->board->majorcategory->id }}"> {{ Str::after($thread->board->name, '/') }}</span>
                                </div>
                            </div>
                        </div>


                        <div class="mb-2 pt-2">
                            <span class="fw-bold fs-5">{{ $thread->title }}</span>
                        </div>

                        <div class="content-preview fs-6 mb-2">
                            {{ $thread->content}}
                        </div>
                    </a>

                    <div class="d-flex justify-content-end">

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
                    </div>
                </div>
                @endforeach

            </div>

        </div>
    </div>
</div>

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