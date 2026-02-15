@extends('layouts.app')

@section('content')

<div class="auth-background">
  <header>
    <div class="auth-header">
      <div class="ms-3 mt-3 text-white">
        <h1>STUDENT BBS</h1>
        <h1>CAMPUS BOARD</h1>
      </div>
      <div>
        <img src="{{ asset('img/mascot.png') }}" class="header-img mt-4 me-3">
      </div>

  </header>
  <main>
    <div class="container">
      <div class="row d-flex justify-content-center py-4 px-2">
        <div class="col-md-5 auth-form">
          <h1 class="text-center auth-form-heading mt-5">
            アカウントを作成
          </h1>
          <form action="{{ route('register') }}" method="POST" x-data="{ submitting: false }" @submit="submitting = true">
            @csrf

            <div class="form-group mt-4">
              <label for="nickname" class="col-form-label ps-2">ニックネーム</label>
              <input id="nickname" type="text" class="form-control @error('nickname') is-invalid  @enderror hokkai-board-register-input py-2" name="nickname" value="{{ old('nickname') }}" required autocomplete="nickname" autofocus placeholder="ニックネーム(※本名は避けてください)">

              @error('nickname')
              <div class="invalid-feedback ps-2">
                {{ $message }}
              </div>
              @enderror
            </div>

            <div class="form-group">
              <label for="email" class="col-form-label ps-2 pb-0">大学のメールアドレス</label>
              <input id="email" type="text" class="form-control @error('email') is-invalid  @enderror hokkai-board-register-input py-2 mt-2" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="対応大学のメールアドレス限定">

              @error('email')
              <div class="invalid-feedback ps-2">
                {{ $message }}
              </div>
              @enderror
              @if( session( 'error_message') )
              <div class="auth-form-error ps-2">
                <span>{{session( 'error_message' ) }}</span>
              </div>
              @endif
            </div>

            <div class="form-group ps-2 ">
              <a href="{{ route('universities.index') }}" class="index-added-university prevent-double-click">
                <p class="mb-0 mt-1">対応大学一覧はこちら</p>
              </a>
            </div>

            <div class="form-group ps-2">
              <a href="{{ route('university-request.create') }}" class="add-university-request prevent-double-click">
                <p class="mb-0">リストにない大学の追加リクエストはこちら</p>
              </a>
            </div>

            <div class="form-group">
              <label for="password" class="col-form-label ps-2 mt-1">パスワード<small class="text-muted ps-2">※8文字以上、英字と数字を含めてください</small></label>

              <input id="password" type="password" class="form-control @error('password') is-invalid  @enderror hokkai-board-register-input py-2" name="password" required autocomplete="new-password" autofocus placeholder="パスワード">

              @error('password')
              <div class="invalid-feedback ps-2">
                {{ $message }}
              </div>
              @enderror
            </div>

            <div class="form-group">
              <label for="password-confirm" class="col-form-label ps-2">パスワード確認</label>
              <input id="password-confirm" type="password" class="form-control hokkai-board-register-input py-2" name="password_confirmation" required autocomplete="new-password" autofocus placeholder="パスワード確認">
            </div>

            <div class="form-group mt-1 ps-2 mb-2">
              <input type="checkbox" name="agree" id="agreeCheck" class="ms-2" {{ old('agree') ? 'checked' : '' }} disabled>
              <label for="agreeCheck" class="col-form-label">
                <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal" class="text-decoration-underline text-primary">利用規約</a>
                に同意する
              </label>
            </div>
            <div class="d-flex justify-content-center">
              <button type="submit" class="register-link d-flex justify-content-center mb-5 px-5 w-100" :disabled="submitting">
                <span x-show="!submitting">
                  <h3 class="py-2 text-center register-link-text text-white">新規登録</h3>
                </span>

                <span x-show="submitting">
                  <div class="d-flex align-items-center justify-content-center py-2">
                    <span class="spinner-border spinner-border-sm me-2 text-white" role="status" aria-hidden="true"></span>
                    <h3 class="register-link-text text-white mb-0">登録中...</h3>
                  </div>
                </span>
              </button>
            </div>
          </form>
        </div>
        <a href="{{ route('login') }}" class="mt-2 prevent-bouble-click text-secondary text-decoration-underline">&larr; ログイン画面に戻る</a>
      </div>
    </div>
  </main>
  <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="termsModalLabel">利用規約</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="termsModalBody">
          {{-- 利用規約の本文 (バランス型・法的リスク対策版) --}}
          <div class="p-2 text-break" style="font-size: 0.9rem; line-height: 1.6;">
            <h6 class="fw-bold mt-3">第1条（適用）</h6>
            <p>本規約は、Campus Board運営事務局（以下「運営者」といいます。）が提供する「Campus Board」（以下「本サービス」といいます。）の利用条件を定めるものです。</p>
            <p>利用者（第2条で定義します。）は、本規約に同意の上、本規約に従って本サービスを利用します。</p>

            <h6 class="fw-bold mt-3">第2条（定義）</h6>
            <p>本規約において用いる用語の定義は、次のとおりとします。</p>
            <ol class="ps-3">
              <li>「利用者」：第4条に基づきアカウント登録を行い、本サービスを利用する者</li>
              <li>「登録情報」：大学発行メールアドレス、表示名、プロフィール、その他運営者が登録を求める情報</li>
              <li>「投稿コンテンツ」：利用者が本サービスに投稿・送信・アップロードした文章、画像、リンク、その他一切の表現物</li>
              <li>「投稿データ等」：投稿コンテンツに加え、投稿日時、掲示板・スレッド情報、通報情報、操作ログ、IPアドレス、端末情報、Cookie等の識別子、閲覧・リアクション等の利用状況データを含む、本サービス利用に関連して生成・取得される一切のデータ</li>
              <li>「派生データ」：投稿データ等を集計・分析・加工して得られる統計情報、指標、ランキング、レポート、学習用データセット、モデル、特徴量、スコアその他の二次的成果物（個人が識別されない形に加工されたものを含みます。）</li>
            </ol>

            <h6 class="fw-bold mt-3">第3条（対象者・非公式性）</h6>
            <p>本サービスは、大学（短期大学を含みます。）に在籍する学生のみが利用できます。本サービスは、いかなる大学その他の団体の公式サービスでもなく、提携・後援・承認を受けているものではありません。</p>

            <h6 class="fw-bold mt-3">第4条（登録・大学発行メール認証）</h6>
            <p>利用者は、大学が発行するメールアドレスによる認証を完了し、運営者が定める方法で登録を行うものとします。運営者は、登録情報に虚偽等がある場合、又は利用者が大学生でない疑いがある場合、事前通知なく利用停止等の措置を行うことができます。</p>

            <h6 class="fw-bold mt-3">第5条（匿名性の範囲・ログ保存）</h6>
            <p>本サービスは、他の利用者に対して投稿者を匿名で表示する機能を提供しますが、運営者は、本人確認、不正防止、権利侵害対応、法令遵守のため、投稿データ等を取得・保存します。したがって、運営者は利用者に対し「完全な匿名性」を保証しません。</p>
            <p>運営者は、法令に基づく開示請求（裁判所からの命令や捜査機関からの照会等）があった場合、または人の生命・身体・財産の保護のために必要があると判断した場合、<strong>利用者の同意なくこれらの情報を開示することがあります。</strong></p>

            <h6 class="fw-bold mt-3">第6条（禁止事項）</h6>
            <p>利用者は、本サービスの利用にあたり、次の行為をしてはなりません。</p>
            <ul class="ps-3">
              <li>法令又は公序良俗に違反する行為、犯罪行為又はこれを助長・勧誘する行為</li>
              <li>他人の名誉権、プライバシー、肖像権、著作権その他一切の権利利益を侵害する行為</li>
              <li>他人の個人情報（氏名、連絡先、顔写真、SNSアカウント等）を、本人の同意なく投稿・公開・晒す行為</li>
              <li>差別、脅迫、嫌がらせ、執拗な誹謗中傷、いじめ、ストーキング、又はこれらを扇動する行為</li>
              <li>大学の内部情報、非公開情報を漏洩する行為</li>
              <li>異性との出会いを主たる目的とする行為</li>
            </ul>

            <h6 class="fw-bold mt-3">第7条（投稿コンテンツの取扱い・利用許諾）</h6>
            <p>投稿コンテンツに関する著作権は、利用者又は正当な権利者に留保されます。</p>
            <p>利用者は運営者に対し、投稿コンテンツを、無償で、地域・期間の制限なく、撤回不能で、非独占的に利用（複製、公衆送信、編集、改変、第三者提供等を含む）する権利を許諾します。</p>
            <p>利用者は、運営者及び運営者から許諾を受けた第三者に対し、投稿コンテンツに関する<strong>著作者人格権を行使しない</strong>ものとします。</p>
            <p>運営者は、投稿データ等を分析・統計化して<strong>「派生データ（統計情報など）」</strong>を作成し、これを運営者の資産として利用（第三者への提供・販売を含む）する権利を有します。</p>

            <h6 class="fw-bold mt-3">第8条（免責事項・責任の制限）</h6>
            <p>運営者は、本サービスに関して、利用者と他の利用者または第三者との間において生じた紛争（誹謗中傷、喧嘩、特定行為など）について、一切関与せず、責任を負いません。利用者は自己の責任と費用で解決するものとします。</p>
            <p>運営者は、本サービスの利用により利用者に生じた損害について、<strong>運営者に故意または重過失がある場合を除き、賠償責任を負いません。</strong></p>

            <p class="text-end mt-5">以上</p>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <span id="scroll-msg" class="text-muted small me-auto">最後までスクロールしてください</span>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const modalBody = document.getElementById('termsModalBody');
      const checkbox = document.getElementById('agreeCheck');
      const hint = document.getElementById('terms-hint');
      const scrollMsg = document.getElementById('scroll-msg');
      let isRead = false;

      modalBody.addEventListener('scroll', function() {
        // スクロール位置の計算 (現在位置 + 表示高さ)
        const scrollPosition = modalBody.scrollTop + modalBody.clientHeight;
        // コンテンツ全体の高さ
        const scrollHeight = modalBody.scrollHeight;

        // 下端から10px以内までスクロールしたら「読んだ」とみなす
        if (scrollHeight - scrollPosition <= 10) {
          if (!isRead) {
            isRead = true;
            checkbox.disabled = false;

            if (hint) hint.style.display = 'none';
            if (scrollMsg) {
              scrollMsg.textContent = "確認ありがとうございます。チェックボックスが有効になりました。";
              scrollMsg.classList.remove('text-muted');
              scrollMsg.classList.add('text-success');
            }
          }
        }
      });
    });
  </script>
  @endsection