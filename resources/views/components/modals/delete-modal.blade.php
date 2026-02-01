@props(['name', 'action','post'])

<div class="modal fade" id="delete-post-modal-{{ $post->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5 ms-1" id="staticBackdropLabel">{{ $name }}を削除する</h1>
        <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ $action }}" method="POST">
        @csrf
        @method('delete')
        <div class="modal-body">
          <span class="col-form-label fw-bold">{{ $name }}を削除します。本当によろしいですか？</span>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
          <button type="submit" class="btn btn-primary">削除する</button>
        </div>
      </form>
    </div>
  </div>
</div>