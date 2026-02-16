@props(['type'])

<button type="button" class="btn p-0 border-0 post-like-btn" data-url="{{ route('posts.like', $type) }}">
  @if($type->isLikedBy(Auth::user()))
  <i class="fa-solid fa-heart fa-lg text-danger post-like-icon"></i>
  @else
  <i class="fa-regular fa-heart fa-lg text-secondary post-like-icon"></i>
  @endif
</button>
<span class="ms-2 post-like-count">
  {{ $type->likes()->count() }}
</span>