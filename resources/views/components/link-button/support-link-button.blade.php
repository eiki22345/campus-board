<div class="support-link-button">
  <div class="row g-0 pt-3">
    <div class="col-4 board-index-icon">
      <a href="{{ request()->fullUrlWithQuery(['sort' => 'popular'])}}" class="board-index-icon-link prevent-double-click">
        <img src="{{ asset('img/fire.png') }}" class="board-index-icon-img">
        <p class="text-center fw-bold mb-0 {{ $sort === 'popular' ? 'text-dark' : 'text-black-50' }}">人気</p>
      </a>
    </div>
    <div class="col-4 board-index-icon">
      <a class="board-index-icon-link" data-bs-toggle="offcanvas" href="#offcanvasTop" role="button" aria-controls="offcanvasTop">
        <img src="{{ asset('img/university.png') }}" class="board-index-icon-img">
        <p class="text-center fw-bold mb-0">ジャンル一覧</p>
      </a>
    </div>
    <div class="col-4 board-index-icon">
      <a href="{{ request()->fullUrlWithQuery(['sort' => 'new'])}}" class="board-index-icon-link prevent-double-click">
        <img src="{{ asset('img/new.png') }}" class="board-index-icon-img">
        <p class="text-center fw-bold mb-0 {{ $sort === 'new' ? 'text-dark' : 'text-black-50' }}">新着</p>
      </a>
    </div>
  </div>
  <div class="row g-0">
    <div class="col-4 board-index-icon py-1">
      <a href="#" class="board-index-icon-link prevent-double-click">
        <img src="{{ asset('img/usage.png') }}" class="board-index-icon-img">
        <p class="text-center fw-bold mb-0">使い方</p>
      </a>
    </div>
    <div class="col-4 board-index-icon py-1">
      <a href="{{ route('mypage') }}" class="board-index-icon-link prevent-double-click">
        <img src="{{ asset('img/history.png') }}" class="board-index-icon-img">
        <p class="text-center fw-bold mb-0">履歴</p>
      </a>
    </div>
    <div class="col-4 board-index-icon py-1">
      <a href="{{ route('users.edit') }}" class="board-index-icon-link prevent-double-click">
        <img src="{{ asset('img/system.png') }}" class="board-index-icon-img">
        <p class="text-center fw-bold mb-0">設定</p>
      </a>
    </div>
  </div>
</div>