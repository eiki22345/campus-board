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
      >
      <a href="{{ route('threads.index', $board->id ) }}">
        {{ $board->name }}
      </a>
      >チャット
    </div>

    <div class="w-80">
      <h3>{{ $thread->title }}</h3>
      <span>>>{{ $thread->content }}</span>
      <hr>


    </div>


    <div class="col-md-7 mx-auto thread-col">
      <div class="create-thread">
        <button type="button" class="create-thread-btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
          <img src="{{ asset('img/create.png') }}" class="create-img">
        </button>
      </div>
      <x-modals.create-post :thread='$thread' />
    </div>
  </div>
</main>