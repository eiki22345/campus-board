@props(['thread', 'post'])

<div class="modal fade" id="replyModal-{{ $post->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">No.{{ $post->post_number }}への返信を作成</h1>
        <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action='{{ route('posts.mention', [$thread , $post]) }}' method="POST">
        @csrf

        <div class="modal-body">
          <div class="form-group mt-1">
            <label for="content" class="col-form-label fw-bold ps-2">返信内容（必須）</label>
            <textarea class="form-control @error('content') is-invalid  @enderror hokkai-board-login-input pb-5" name="content" required autocomplete="content" placeholder="例：◯◯の講義とか楽でおすすめです!">{{ old('content') }}</textarea>
            @error('content')
            <div class="invalid-feedback ps-2">
              {{ $message }}
            </div>
            @enderror
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
          <button type="submit" class="btn btn-primary">作成する</button>
        </div>
      </form>
    </div>
  </div>
</div>