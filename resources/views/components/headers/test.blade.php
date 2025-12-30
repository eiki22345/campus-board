<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">トピックを作成する</h1>
        <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-group mt-4">
          <label for="title" class="col-form-label fw-bold ps-2">トピックタイトル（必須）</label>
          <input type="text" class="form-control @error('title') is-invalid  @enderror hokkai-board-login-input py-2" name="title" value="{{ old('title') }}" required autocomplete="title" placeholder="例：おすすめの講義教えて">

          @error('title')
          <div class="invalid-feedback ps-2">
            {{ $message }}
          </div>
          @enderror
        </div>
        <div class="form-group mt-4">
          <label for="content" class="col-form-label fw-bold ps-2">本文（必須）</label>
          <textarea class="form-control @error('content') is-invalid  @enderror hokkai-board-login-input py-2" name="content" required autocomplete="content" placeholder="例：後期で単位やばいので、出席がなくて楽な講義知りたいです。">{{ old('content') }}</textarea>

          @error('content')
          <div class="invalid-feedback ps-2">
            {{ $message }}
          </div>
          @enderror
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Understood</button>
      </div>
    </div>
  </div>
</div>