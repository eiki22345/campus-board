@extends('layouts.app')

@section('content')

<header>
    <x-headers.header :user_university='$user_university' :university_boards='$university_boards' :common_boards='$common_boards' />

    <x-headers.header-search />
</header>
<main>
    <div class="col-md-8 mx-auto">
        <div class="background-img img-fluid">
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
        </div>
    </div>
</main>

@endsection