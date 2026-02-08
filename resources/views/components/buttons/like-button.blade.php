@props(['type'])

<button type="button" class="btn p-0 border-0 post-like-btn" data-url="{{ route('posts.like', $type) }}">
  @if($type->isLikedBy(Auth::user()))
  {{-- いいね済み：赤色の塗りつぶし --}}
  <i class="fa-solid fa-heart fa-lg text-danger post-like-icon"></i>
  @else
  {{-- 未いいね：灰色の枠線 --}}
  <i class="fa-regular fa-heart fa-lg text-secondary post-like-icon"></i>
  @endif
</button>
{{-- いいね数 --}}
<span class="ms-2 post-like-count">
  {{ $type->likes()->count() }}
</span>