@extends('layouts.app')

@section('content')
<main>

  <body class="legal-page-body">
    <div class="container py-5" x-data="{ currentTab: 'terms' }">
      <div class="row g-4">
        <div class="col-md-4 col-lg-3">
          <div class="legal-sidebar">
            <h5 class="legal-sidebar-title">規約・ガイドライン</h5>
            <div class="list-group legal-menu-list">
              <button type="button"
                class="list-group-item list-group-item-action legal-menu-item"
                :class="{ 'active': currentTab === 'terms' }"
                @click="currentTab = 'terms'">
                利用規約
              </button>
              <button type="button"
                class="list-group-item list-group-item-action legal-menu-item"
                :class="{ 'active': currentTab === 'privacy' }"
                @click="currentTab = 'privacy'">
                プライバシーポリシー
              </button>
              <button type="button"
                class="list-group-item list-group-item-action legal-menu-item"
                :class="{ 'active': currentTab === 'deletion' }"
                @click="currentTab = 'deletion'">
                削除ガイドライン
              </button>
              <button type="button"
                class="list-group-item list-group-item-action legal-menu-item"
                :class="{ 'active': currentTab === 'community' }"
                @click="currentTab = 'community'">
                コミュニティガイドライン
              </button>
            </div>

            <div class="mt-4 px-2">
              <a href="{{ route('dashboard') }}" class="legal-back-link prevent-double-click">
                <small>← トップページへ戻る</small>
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-8 col-lg-9">
          <div class="legal-content-card shadow-sm">

            <div x-show="currentTab === 'terms'" x-cloak x-transition>
              <h2 class="legal-content-title">利用規約</h2>
              <div class="legal-content-placeholder">
                <p class="mb-4">この利用規約（以下，「本規約」といいます。）は，Campus Board運営事務局（以下「運営者」といいます。）が提供する「Campus Board」（以下「本サービス」といいます。）の利用条件を定めるものです。利用者（第2条で定義します。）は、本規約に同意の上、本規約に従って本サービスを利用します。</p>

                <section class="mb-4">
                  <h6 class="fw-bold">第1条（適用）</h6>
                  <p>本規約は、運営者が提供する本サービスの利用条件を定めるものです。利用者は、本規約に同意の上、本規約に従って本サービスを利用します。</p>
                </section>

                <section class="mb-4">
                  <h6 class="fw-bold">第2条（定義）</h6>
                  <p>本規約において用いる用語の定義は、次のとおりとします。</p>
                  <ul class="list-unstyled ps-3">
                    <li><strong>「利用者」：</strong>第4条に基づきアカウント登録を行い、本サービスを利用する者</li>
                    <li><strong>「登録情報」：</strong>大学発行メールアドレス、表示名、プロフィール、その他運営者が登録を求める情報</li>
                    <li><strong>「投稿コンテンツ」：</strong>利用者が本サービスに投稿・送信・アップロードした文章、画像、リンク、その他一切の表現物</li>
                    <li><strong>「投稿データ等」：</strong>投稿コンテンツに加え、投稿日時、掲示板・スレッド情報、通報情報、操作ログ、IPアドレス、端末情報、Cookie等の識別子、閲覧・リアクション等の利用状況データを含む、本サービス利用に関連して生成・取得される一切のデータ</li>
                    <li><strong>「派生データ」：</strong>投稿データ等を集計・分析・加工して得られる統計情報、指標、ランキング、レポート、学習用データセット、モデル、特徴量、スコアその他の二次的成果物（個人が識別されない形に加工されたものを含みます。）</li>
                  </ul>
                </section>

                <section class="mb-4">
                  <h6 class="fw-bold">第3条（対象者・非公式性）</h6>
                  <p>本サービスは、大学（短期大学を含みます。）に在籍する学生のみが利用できます。本サービスは、いかなる大学その他の団体の公式サービスでもなく、提携・後援・承認を受けているものではありません。</p>
                </section>

                <section class="mb-4">
                  <h6 class="fw-bold">第4条（登録・大学発行メール認証）</h6>
                  <p>利用者は、大学が発行するメールアドレスによる認証を完了し、運営者が定める方法で登録を行うものとします。運営者は、登録情報に虚偽等がある場合、又は利用者が大学生でない疑いがある場合、事前通知なく利用停止等の措置を行うことができます。</p>
                </section>

                <section class="mb-4">
                  <h6 class="fw-bold">第5条（匿名性の範囲・ログ保存）</h6>
                  <ol class="ps-3">
                    <li class="mb-2">本サービスは、他の利用者に対して投稿者を匿名で表示する機能を提供しますが、運営者は、本人確認、不正防止、権利侵害対応、法令遵守のため、投稿データ等を取得・保存します。したがって、<strong>運営者は利用者に対し「完全な匿名性」を保証しません。</strong></li>
                    <li>運営者は、法令に基づく開示請求（裁判所からの命令や捜査機関からの照会等）があった場合、または人の生命・身体・財産の保護のために必要があると判断した場合、<strong>利用者の同意なくこれらの情報を開示することがあります。</strong></li>
                  </ol>
                </section>

                <section class="mb-4">
                  <h6 class="fw-bold">第6条（禁止事項）</h6>
                  <p>利用者は、本サービスの利用にあたり、次の行為をしてはなりません。</p>
                  <ul class="ps-3">
                    <li>法令又は公序良俗に違反する行為、犯罪行為又はこれを助長・勧誘する行為</li>
                    <li>他人の名誉権、プライバシー、肖像権、著作権その他一切の権利利益を侵害する行為</li>
                    <li>他人の個人情報（氏名、連絡先、顔写真、SNSアカウント等）を、本人の同意なく投稿・公開・晒す行為</li>
                    <li>差別、脅迫、嫌がらせ、執拗な誹謗中傷、いじめ、ストーキング、又はこれらを扇動する行為</li>
                    <li>大学の内部情報、非公開情報を漏洩する行為</li>
                    <li>異性との出会いを主たる目的とする行為</li>
                  </ul>
                </section>

                <section class="mb-4">
                  <h6 class="fw-bold">第7条（投稿コンテンツの取扱い・利用許諾）</h6>
                  <ol class="ps-3">
                    <li class="mb-2">投稿コンテンツに関する著作権は、利用者又は正当な権利者に留保されます。</li>
                    <li class="mb-2">利用者は運営者に対し、投稿コンテンツを、無償で、地域・期間の制限なく、撤回不能で、非独占的に利用（複製、公衆送信、編集、改変、第三者提供等を含む）する権利を許諾します。</li>
                    <li class="mb-2">利用者は、運営者及び運営者から許諾を受けた第三者に対し、<strong>投稿コンテンツに関する著作者人格権を行使しないものとします。</strong></li>
                    <li>運営者は、投稿データ等を分析・統計化して<strong>「派生データ（統計情報など）」を作成し、これを運営者の資産として利用（第三者への提供・販売を含む）する権利を有します。</strong></li>
                  </ol>
                </section>

                <section class="mb-4">
                  <h6 class="fw-bold">第8条（免責事項・責任の制限）</h6>
                  <ol class="ps-3">
                    <li class="mb-2">運営者は、本サービスに関して、利用者と他の利用者または第三者との間において生じた紛争（誹謗中傷、喧嘩、特定行為など）について、一切関与せず、責任を負いません。利用者は自己の責任と費用で解決するものとします。</li>
                    <li>運営者は、本サービスの利用により利用者に生じた損害について、<strong>運営者に故意または重過失がある場合を除き、賠償責任を負いません。</strong></li>
                  </ol>
                </section>

                <p class="text-end mt-5">以上</p>
              </div>
            </div>

            <div x-show="currentTab === 'privacy'" x-cloak x-transition>
              <h2 class="legal-content-title">プライバシーポリシー</h2>
              <div class="legal-content-placeholder">
                <p>ここにプライバシーポリシーの内容が入ります。</p>
              </div>
            </div>

            <div x-show="currentTab === 'deletion'" x-cloak x-transition>
              <h2 class="legal-content-title">削除ガイドライン</h2>
              <div class="legal-content-placeholder">
                <p>ここに削除ガイドラインの内容が入ります。</p>
              </div>
            </div>

            <div x-show="currentTab === 'community'" x-cloak x-transition>
              <h2 class="legal-content-title">使い方・コミュニティガイドライン</h2>
              <div class="legal-content-placeholder">

              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
</main>
@endsection