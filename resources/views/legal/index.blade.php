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
                <p class="text-muted small mb-4">最終更新日：2026年2月14日</p>
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
                <p class="text-muted small mb-4">最終更新日：2026年2月14日</p>
                <p class="mb-4">Campus Board（以下「本サービス」といいます）は、利用者の個人情報の重要性を認識し、個人情報保護法その他関連法令を遵守し、適切に取り扱います。</p>

                <section class="mb-4">
                  <h6 class="fw-bold">第1条（取得する情報）</h6>
                  <p>当サービスは、以下の情報を取得します。</p>
                  <ol class="ps-3">
                    <li class="mb-2"><strong>登録情報：</strong>大学発行メールアドレス、認証に必要な情報</li>
                    <li class="mb-2"><strong>技術情報：</strong>IPアドレス、アクセスログ、利用日時、ブラウザ情報、端末情報、Cookie情報</li>
                    <li><strong>投稿関連情報：</strong>投稿内容、画像データ、通報履歴、アカウント利用履歴</li>
                  </ol>
                </section>

                <section class="mb-4">
                  <h6 class="fw-bold">第2条（利用目的）</h6>
                  <p>取得した情報は、以下の目的で利用します。</p>
                  <ul class="list-unstyled ps-3">
                    <li>1. 本サービスの提供および本人確認</li>
                    <li>2. 不正利用の防止</li>
                    <li>3. 利用規約違反への対応</li>
                    <li>4. 権利侵害対応および削除手続</li>
                    <li>5. 発信者情報開示請求その他法令対応</li>
                    <li>6. サービス改善および品質向上</li>
                    <li>7. 統計情報および分析データの作成</li>
                    <li>8. 新機能・新サービスの開発</li>
                    <li>9. マーケティング分析</li>
                    <li>10. 上記目的に関連する業務</li>
                  </ul>
                </section>

                <section class="mb-4">
                  <h6 class="fw-bold">第3条（投稿データの利用および派生データ）</h6>
                  <ol class="ps-3">
                    <li class="mb-2">投稿内容の著作権は原則として投稿者に帰属します。</li>
                    <li class="mb-2">投稿者は、運営者に対し、投稿データを無償・非独占的・無期限・地域制限なく、複製、公衆送信、翻案、編集、分析、統計化、研究利用、AI学習利用、再許諾を含む一切の方法で利用することを許諾するものとします。</li>
                    <li class="mb-2">当サービスは、投稿データおよび利用履歴を分析し、個人を識別できない形式に加工された<strong>「派生データ」</strong>を作成することがあります。</li>
                    <li class="mb-2">当サービスは、当該派生データを自己の資産として、第三者への提供、公開、販売、研究、マーケティング等の方法で利用することができます。</li>
                    <li>当サービスは、派生データについて特定の個人を再識別する行為を行いません。</li>
                  </ol>
                </section>

                <section class="mb-4">
                  <h6 class="fw-bold">第4条（第三者提供）</h6>
                  <p>当サービスは、以下の場合を除き、個人情報を第三者に提供しません。</p>
                  <ul class="list-unstyled ps-3">
                    <li>1. 本人の同意がある場合</li>
                    <li>2. 法令に基づく場合</li>
                    <li>3. 裁判所の命令または捜査機関からの適法な照会があった場合</li>
                    <li>4. 個人を識別できない形式に加工された統計情報または派生データの場合</li>
                  </ul>
                </section>

                <section class="mb-4">
                  <h6 class="fw-bold">第5条〜第7条（管理・開示）</h6>
                  <p>当サービスは、利用目的達成に必要な期間、情報を保存し、合理的な安全管理措置を講じます。裁判所の命令等がある場合、情報を開示することがあります。</p>
                </section>

                <section class="mb-4">
                  <h6 class="fw-bold">第8条（開示・訂正・利用停止）</h6>
                  <p>利用者は、法令に基づき自己の個人情報の開示、訂正、削除等を求めることができます。お問い合わせフォームよりご連絡ください。</p>
                </section>

                <section class="mb-4">
                  <h6 class="fw-bold">第9条〜第11条（その他）</h6>
                  <p>本サービスはCookie等を使用することがあります。未成年者は保護者の同意を得て利用してください。本ポリシーは必要に応じて改定されます。</p>
                </section>

                <div class="mt-5 pt-3 border-top">
                  <h6 class="fw-bold">お問い合わせ窓口</h6>
                  <p>Campus Board 運営<br><small class="text-muted">（お問い合わせフォームよりご連絡ください）</small></p>
                </div>
              </div>
            </div>

            <div x-show="currentTab === 'deletion'" x-cloak x-transition>
              <h2 class="legal-content-title">削除ガイドライン</h2>
              <div class="legal-content-placeholder">
                <p class="text-muted small mb-4">最終更新日：2026年2月14日</p>
                <p class="mb-4">本ガイドラインは、Campus Board（以下「本サービス」といいます）に投稿された情報について、第三者の権利侵害が疑われる場合の対応方針を定めるものです。</p>

                <section class="mb-4">
                  <h6 class="fw-bold">第1条（基本方針）</h6>
                  <ol class="ps-3">
                    <li>本サービスは大学生限定の匿名掲示板です。</li>
                    <li>投稿内容の法的責任は投稿者に帰属します。</li>
                    <li>当運営は原則としてユーザー間の紛争に関与しません。</li>
                    <li>法令に基づき明らかな権利侵害が認められる場合は、送信防止措置（削除）を含む必要な対応を行います。</li>
                  </ol>
                </section>

                <section class="mb-4">
                  <h6 class="fw-bold">第2条（削除対象となる情報）</h6>
                  <p>以下に該当する可能性がある情報は、削除対象となる場合があります。</p>
                  <ul class="ps-3 mb-2">
                    <li><strong>（1）名誉毀損：</strong>社会的評価を低下させる具体的事実の摘示、犯罪歴等の断定的な記載</li>
                    <li><strong>（2）プライバシー侵害：</strong>実名、住所、電話番号、SNS特定、顔写真の無断掲載</li>
                    <li><strong>（3）著作権侵害：</strong>許可のない文章・画像・資料の転載、有料コンテンツの無断公開</li>
                    <li><strong>（4）公序良俗違反：</strong>犯罪予告、リベンジポルノ、自殺予告、違法行為の勧誘</li>
                    <li><strong>（5）その他法令違反</strong></li>
                  </ul>
                </section>

                <section class="mb-4">
                  <h6 class="fw-bold">第3条（削除依頼の方法）</h6>
                  <ol class="ps-3">
                    <li>削除依頼は、本サービス内の「通報（Report）機能」からのみ受け付けます。</li>
                    <li>DM、SNS、匿名投稿による削除依頼は受け付けません。</li>
                    <li>通報時には「名誉毀損」「プライバシー侵害」等の理由を適切に選択してください。</li>
                    <li>理由の記載が不十分な場合、削除対応ができない場合があります。</li>
                  </ol>
                </section>

                <section class="mb-4">
                  <h6 class="fw-bold">第4条（削除判断プロセス）</h6>
                  <ol class="ps-3">
                    <li>当運営は通報内容を確認し、権利侵害の相当性を判断します。</li>
                    <li>必要に応じて、当該投稿者に対し削除への意見照会を行うことがあります。</li>
                    <li>投稿者から7日以内に合理的な反論がない場合、当運営の判断で削除することがあります。</li>
                    <li>投稿者が削除に同意した場合は、速やかに削除します。</li>
                  </ol>
                </section>

                <section class="mb-4">
                  <h6 class="fw-bold">第5条（即時削除の例外）</h6>
                  <p>リベンジポルノ、生命身体に差し迫った危険がある投稿、明確な犯罪予告、実名の晒し行為等は照会なしに即時削除することがあります。</p>
                </section>

                <section class="mb-4">
                  <h6 class="fw-bold">第6条（発信者情報の開示）</h6>
                  <p>裁判所の命令、法令に基づく開示請求、捜査機関からの適法な照会があった場合、当運営は保有する情報を開示することがあります。</p>
                </section>

                <section class="mb-4">
                  <h6 class="fw-bold">第7条（免責事項）</h6>
                  <ol class="ps-3">
                    <li>削除または非削除の判断により生じた損害について、当運営は故意または重過失がある場合を除き責任を負いません。</li>
                    <li>当運営は投稿内容を事前に監視する義務を負いません。</li>
                  </ol>
                </section>

                <p class="text-end mt-5">以上</p>
              </div>
            </div>
            <div x-show="currentTab === 'community'" x-cloak x-transition>
              <h2 class="legal-content-title">コミュニティガイドライン</h2>
              <div class="legal-text-content">
                <p class="mb-4 text-dark fw-bold">Campus Boardへようこそ！</p>
                <p class="mb-4">本サービスは、大学生が「安心・安全」に本音で交流できる場所を目指しています。みんなで楽しく、有意義なコミュニティを作るために、以下のマナーを守って活用してください。</p>

                <section class="mb-4">
                  <h6 class="fw-bold text-primary">1. 相手への敬意を忘れずに</h6>
                  <p>Campus Boardは匿名（ニックネーム）での交流ですが、画面の向こうには自分と同じ「一人の学生」がいます。意見が違う相手に対しても、過度な攻撃や暴言を避け、節度ある言葉遣いを心がけましょう。</p>
                </section>

                <section class="mb-4">
                  <h6 class="fw-bold text-primary">2. プライバシーを守ろう（特定禁止）</h6>
                  <p>自分や他人の「本名」「住所」「電話番号」「SNSアカウント」などを書き込むことは、トラブルの原因になるため禁止です。また、「〇〇学部の〇〇さん」といった、個人が特定できる情報の書き込みも控えましょう。</p>
                </section>

                <section class="mb-4">
                  <h6 class="fw-bold text-primary">3. 大学の情報を正しく扱おう</h6>
                  <p>授業の感想やサークル情報の共有は大歓迎です！ただし、学外に漏らすべきではない高度な機密情報や、試験問題の不正な共有などは避け、大学の一員として責任ある行動をお願いします。</p>
                </section>

                <section class="mb-4">
                  <h6 class="fw-bold text-primary">4. 掲示板（カテゴリ）の使い分け</h6>
                  <ul class="ps-3">
                    <li class="mb-2"><strong>大学限定板：</strong>自分の大学の学生しか見られません。ローカルな話題（学食、授業、サークル等）に最適です。</li>
                    <li><strong>共通板：</strong>他大学の学生とも交流できます。就活、恋愛、アルバイトなど、広い意見を聞きたい時に活用しましょう。</li>
                  </ul>
                </section>

                <section class="mb-4">
                  <h6 class="fw-bold text-primary">5. 自演防止とハッシュID</h6>
                  <p>スレッド内では、投稿者ごとに固定の「ハッシュID」が表示されます。一人で何人ものフリをして議論を操作する「自演行為」は、コミュニティの信頼を損なうため、推奨されません。</p>
                </section>

                <section class="mb-4">
                  <h6 class="fw-bold text-primary">6. 困ったときは「通報機能」を</h6>
                  <p>不快な投稿や、規約に違反している投稿を見かけたら、反論して喧嘩をするのではなく、静かに「通報（Report）」ボタンを押してください。運営チームが内容を確認し、適切に対処します。</p>
                </section>

                <div class="bg-light p-3 rounded mt-5 border-start border-primary border-4">
                  <p class="mb-0 small text-muted">※これらのガイドラインは、すべての学生にとって最高の居場所を作るためのものです。違反が続く場合は、アカウント停止などの措置をとる場合もあります。みんなでCampus Boardを盛り上げていきましょう！</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</main>
@endsection