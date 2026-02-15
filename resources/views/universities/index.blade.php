@extends('layouts.app')

@section('title', '大学一覧')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-10">

      {{-- タイトルエリア --}}
      <div class="text-center mb-5">
        <h1 class="fw-bold mb-3">大学を探す</h1>
        <p class="text-muted">お探しの大学名やドメインを入力してください</p>
      </div>

      {{-- 検索コンポーネントの呼び出し --}}
      {{-- コンポーネント内に <div class="col-md-8 mx-auto"> があるため、ここでは直接配置します --}}
      <div class="mb-5">
        <x-headers.header-search
          :action="route('universities.index')"
          placeholder="大学名またはドメインで検索 (例: 北海道大学, hokudai.ac.jp)"
          :keyword="$keyword" />
      </div>

      {{-- 検索結果の表示エリア --}}
      <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm">

        {{-- 検索結果が一件もない場合 --}}
        @if($regions->pluck('universities')->flatten()->isEmpty())
        <div class="text-center py-5">
          <div class="mb-3 text-muted" style="font-size: 3rem;">
            <i class="fa-regular fa-face-sad-tear"></i>
          </div>
          <h4 class="text-muted">「{{ $keyword }}」に一致する大学は見つかりませんでした。</h4>
          <a href="{{ route('universities.index') }}" class="btn btn-link mt-2">すべての大学を表示</a>
        </div>
        @else

        {{-- 地域ごとのループ --}}
        @foreach($regions as $region)
        {{-- その地域に（検索条件に合う）大学がある場合のみ表示 --}}
        @if($region->universities->isNotEmpty())
        <div class="mb-5">
          {{-- 地域名ヘッダー --}}
          <div class="d-flex align-items-center mb-3 pb-2 border-bottom border-2 border-primary-subtle">
            <h3 class="h4 fw-bold text-primary mb-0 me-2">
              <i class="fa-solid fa-location-dot me-2"></i>{{ $region->name }}
            </h3>
            <span class="badge bg-secondary-subtle text-secondary rounded-pill">
              {{ $region->universities->count() }}校
            </span>
          </div>

          {{-- 大学カードグリッド --}}
          <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
            @foreach($region->universities as $university)
            <div class="col">
              <div class="card h-100 border hover-shadow transition-all">
                <div class="card-body">
                  <h5 class="card-title fw-bold text-dark mb-1">
                    {{ $university->name }}
                  </h5>
                  <p class="card-text text-muted small mb-0">
                    <i class="fa-solid fa-globe me-1"></i>{{ $university->email_domain }}
                  </p>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
        @endif
        @endforeach

        @endif
      </div>

    </div>
  </div>
</div>

<style>
  /* ホバー時の浮き上がりエフェクト */
  .hover-shadow:hover {
    transform: translateY(-3px);
    box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
    border-color: #0d6efd !important;
  }

  .transition-all {
    transition: all 0.3s ease;
  }
</style>
@endsection