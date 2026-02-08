@props(['name','action','post', 'type'])

<div class="modal fade" id="delete-{{ $type }}-modal-{{ $post->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5 ms-1" id="staticBackdropLabel">{{ $name }}を削除する</h1>
        <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ $action }}" method="POST" x-data="{ submitting: false }" @submit="submitting = true">
        @csrf
        @method('delete')
        <div class="modal-body">
          <span class="col-form-label fw-bold">{{ $name }}を削除します。本当によろしいですか？</span>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
          <button type="submit" class="btn btn-danger" :disabled="submitting">
            <span x-show="!submitting">削除する</span>
            <span x-show="submitting">削除中...</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>