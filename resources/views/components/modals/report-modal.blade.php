@props([
'target_id',
'type',
])

<div class="modal fade" id="reportModal-{{ $type }}-{{ $target_id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          {{ $type === 'post' ? 'コメント' : 'トピック' }}を通報する
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('reports.store') }}" method="POST" x-data="{ submitting: false }" @submit="submitting = true">
        @csrf
        <input type="hidden" name="{{ $type }}_id" value="{{ $target_id }}">

        <div class="modal-body">
          <div x-data="{ selectedReason: '' }">

            <div class="mb-3">
              <label class="form-label fw-bold">通報理由</label>

              <select name="reason" class="form-select" required x-model="selectedReason">
                <option value="" disabled selected>選択してください</option>
                <option value="誹謗中傷">誹謗中傷・迷惑行為</option>
                <option value="スパム">スパム・宣伝</option>
                <option value="不適切なコンテンツ">不適切なコンテンツ</option>
                <option value="other">その他</option>
              </select>
            </div>

            <div class="mb-3"
              x-show="selectedReason === 'other'"
              x-transition
              style="display: none;">
              <label class="form-label small text-muted">
                詳細な理由を入力してください <span class="text-danger">*</span>
              </label>
              <textarea name="reason_detail" class="form-control" rows="3" placeholder="例: 個人情報が含まれている等"></textarea>
            </div>

          </div>

          <div class="alert alert-light border mt-3 small text-muted">
            <i class="bi bi-info-circle"></i> 通報が一定数を超えると、自動的に非表示になります。
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
          <button type="submit" class="btn btn-danger" :disabled="submitting">
            <span x-show="!submitting">通報する</span>
            <span x-show="submitting">送信中...</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>