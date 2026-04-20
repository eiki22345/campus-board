@extends('layouts.app')

@section('title', 'Campus Board｜大学生限定の匿名掲示板SNS')
@section('meta_description', '同じ大学の仲間と本音で語り合える、大学生限定の匿名掲示板「Campus Board」。楽単・就活・学食・サークル情報まで、キャンパスライフに必要な話題がここに。大学メール認証で安心・安全。')


@section('content')
<div class="lp-body">

  {{-- ヘッダー --}}
  <header class="lp-header">
    <div class="lp-header-inner">
      <a href="{{ route('welcome') }}" class="lp-logo">
        <img src="{{ asset('img/mascot.png') }}" alt="Campus Board マスコット">
        <div class="lp-logo-text">
          <div class="sub">STUDENT BBS</div>
          <div class="main">CAMPUS BOARD</div>
        </div>
      </a>
      <nav class="lp-nav">
        <a href="{{ route('login') }}" class="lp-btn lp-btn-outline">ログイン</a>
        <a href="{{ route('register') }}" class="lp-btn lp-btn-filled">新規登録</a>
      </nav>
    </div>
  </header>

  {{-- ヒーロー --}}
  <section class="lp-hero">
    <div class="lp-hero-inner">
      <div class="lp-fade">
        <h1>
          同じ大学の仲間と<br>
          <span class="accent">本音</span>で語り合おう。
        </h1>
        <p class="lp-hero-lead">
          Campus Boardは、大学メール認証で守られた<br>
          <strong>大学生限定の匿名掲示板SNS</strong>。<br>
          楽単情報・就活・学食・恋愛まで、<br class="hidden sm:inline">
          キャンパスライフのリアルな声が集まっています。
        </p>
        <div class="lp-hero-badges">
          <span class="lp-badge">🎓 大学メール認証</span>
          <span class="lp-badge">🔒 完全匿名</span>
          <span class="lp-badge">🆓 完全無料</span>
          <span class="lp-badge">📱 スマホ最適化</span>
        </div>
        <div class="lp-hero-ctas">
          <a href="{{ route('register') }}" class="lp-btn lp-btn-filled lp-btn-lg">無料で新規登録</a>
          <a href="{{ route('login') }}" class="lp-btn lp-btn-outline lp-btn-lg">ログインはこちら</a>
        </div>
      </div>
      <div class="lp-hero-img lp-fade">
        <img src="{{ asset('img/lp-img/thread.jpg') }}" alt="Campus Boardのスレッド一覧画面">
      </div>
    </div>
  </section>

  {{-- 3つの特徴 --}}
  <section class="lp-section">
    <div class="lp-section-inner">
      <div class="lp-section-head lp-fade">
        <span class="lp-section-eyebrow">WHY CAMPUS BOARD</span>
        <h2 class="lp-section-title">Campus Boardが選ばれる3つの理由</h2>
        <p class="lp-section-lead">大学生のリアルな悩みや情報交換に特化した、安心のコミュニティです。</p>
      </div>
      <div class="lp-features">
        <div class="lp-feature-card lp-fade">
          <div class="lp-feature-icon">🎓</div>
          <h3>大学生だけの空間</h3>
          <p>大学発行メールアドレスでの認証制。学生以外は入れない、クローズドなコミュニティだから本音で話せます。</p>
        </div>
        <div class="lp-feature-card lp-fade">
          <div class="lp-feature-icon">🤫</div>
          <h3>完全匿名で安心</h3>
          <p>ニックネーム表示で個人が特定されることはありません。就活・恋愛・悩みごとも気兼ねなく相談できます。</p>
        </div>
        <div class="lp-feature-card lp-fade">
          <div class="lp-feature-icon">📚</div>
          <h3>大学専用＆全国共通の板</h3>
          <p>「自分の大学専用の板」と「全国の大学生が集う板」の両方を用意。楽単情報から恋愛相談まで話題は自由。</p>
        </div>
      </div>
    </div>
  </section>

  {{-- スクショ紹介 --}}
  <section class="lp-section lp-showcase">
    <div class="lp-section-inner">
      <div class="lp-section-head lp-fade">
        <span class="lp-section-eyebrow">FEATURES</span>
        <h2 class="lp-section-title">実際の画面でご紹介</h2>
        <p class="lp-section-lead">シンプルで使いやすいUI。スマホでサクッと確認・投稿できます。</p>
      </div>

      <div class="lp-showcase-row reverse lp-fade">
        <div class="lp-showcase-text">
          <span class="lp-showcase-num">FEATURE 01</span>
          <h3>活発なスレッドで<br>リアルタイムに交流</h3>
          <p>
            各カテゴリで自由にスレッドを立てて、いつでも相談・雑談・情報共有ができます。
            いいね・コメントで気軽にリアクション。
          </p>
          <p>
            通報機能やNGワードフィルタも搭載しているので、荒らしが少なく、
            健全なやりとりが保たれています。
          </p>
        </div>
        <div class="lp-showcase-img">
          <img src="{{ asset('img/lp-img/thread.jpg') }}" alt="スレッド一覧画面">
        </div>
      </div>

      <div class="lp-showcase-row lp-fade">
        <div class="lp-showcase-text">
          <span class="lp-showcase-num">FEATURE 02</span>
          <h3>本音の返信で<br>深いコミュニケーション</h3>
          <p>
            スレッドへの返信はもちろん、返信へのメンションも可能。
            先輩から直接アドバイスをもらったり、後輩に情報を伝えたり。
          </p>
          <p>
            購読機能で気になるスレッドの更新をキャッチ。通知も届くので見逃しません。
          </p>
        </div>
        <div class="lp-showcase-img">
          <img src="{{ asset('img/lp-img/res.jpg') }}" alt="スレッド返信画面">
        </div>
      </div>
    </div>
  </section>

  {{-- 2つの板システム --}}
  <section class="lp-section lp-dualboard">
    <div class="lp-section-inner">
      <div class="lp-section-head lp-fade">
        <span class="lp-section-eyebrow">DUAL BOARD</span>
        <h2 class="lp-section-title">2つの板で、交流の幅が広がる。</h2>
        <p class="lp-section-lead">
          同じ大学の仲間とのディープな情報交換も、全国の大学生との新しい出会いも。<br>
          Campus Boardなら、両方叶います。
        </p>
        <div class="lp-dualboard-stat lp-fade">
          <span class="num">約800</span>
          <span class="unit">校の全国大学・短大に対応</span>
        </div>
        <p class="lp-dualboard-stat-note">
          有名大学からマイナー大学、短期大学まで幅広くカバー。掲載がない大学もリクエストで随時追加中です。
        </p>
      </div>

      <div class="lp-dualboard-grid">
        {{-- 自大学専用板 --}}
        <div class="lp-dualboard-card own lp-fade">
          <span class="lp-dualboard-label">YOUR CAMPUS</span>
          <h3>自分の大学専用板</h3>
          <p class="tagline">同じキャンパスの仲間だけの、クローズドな空間。</p>
          <div class="lp-dualboard-img">
            <img src="{{ asset('img/lp-img/cateogory-1.jpg') }}" alt="自分の大学専用板の画面">
          </div>
          <ul class="lp-dualboard-list">
            <li><strong>同じ大学の学生としか交流できない</strong>ので、身内ならではのリアルな話題が集まります</li>
            <li>楽単・鬼単情報／教授・講義のレビュー／学部・ゼミ選び</li>
            <li>学食・周辺グルメ／サークル勧誘・交流</li>
            <li>同じキャンパスだからこそ分かる、濃密な情報共有</li>
          </ul>
        </div>

        {{-- 全国板 --}}
        <div class="lp-dualboard-card all lp-fade">
          <span class="lp-dualboard-label">NATIONWIDE</span>
          <h3>全国の大学生と交流</h3>
          <p class="tagline">他大学の学生とも繋がれる、オープンな広場。</p>
          <div class="lp-dualboard-img">
            <img src="{{ asset('img/lp-img/category-2.jpg') }}" alt="全国の大学生と交流できる画面">
          </div>
          <ul class="lp-dualboard-list">
            <li><strong>全国約800校の大学生と自由に交流</strong>できる共通エリア</li>
            <li>就活・進路／悩み・恋愛相談／趣味・スポーツ</li>
            <li>グルメ・ファッション／エンタメ・ゲーム／ニュース・雑談</li>
            <li>他大学の視点や情報が、自分の世界を広げてくれる</li>
          </ul>
        </div>
      </div>

      {{-- 自大学専用板は「ログインした大学」によって自動切り替え --}}
      <div class="lp-compare lp-fade">
        <div class="lp-compare-head">
          <span class="lp-compare-eyebrow">AUTO SWITCHING</span>
          <h3>「自分の大学専用板」は、<br>ログインした大学で自動的に切り替わります。</h3>
          <p>
            大学メールでログインすると、あなたの大学名が入った専用の板が自動表示。<br>
            <strong>別の大学の専用板は、あなたには見えません。</strong>
            逆に、あなたの大学の専用板も他大学の学生からは見えないので、安心して本音で話せます。
          </p>
        </div>

        <div class="lp-compare-grid">
          <div class="lp-compare-card">
            <div class="lp-compare-tag">
              <span class="lp-compare-domain">@hokudai.ac.jp</span>
              <span class="lp-compare-arrow">→</span>
              <span class="lp-compare-name">北海道大学の学生</span>
            </div>
            <div class="lp-compare-img">
              <img src="{{ asset('img/lp-img/hokudai.png') }}" alt="北海道大学でログインしたときの表示">
            </div>
            <p class="lp-compare-caption">
              <span class="lp-compare-hl own-hl">「北海道大学専用」</span>の板だけが表示されます
            </p>
          </div>

          <div class="lp-compare-vs">
            <span>VS</span>
          </div>

          <div class="lp-compare-card">
            <div class="lp-compare-tag">
              <span class="lp-compare-domain">@toyo.jp</span>
              <span class="lp-compare-arrow">→</span>
              <span class="lp-compare-name">東洋大学の学生</span>
            </div>
            <div class="lp-compare-img">
              <img src="{{ asset('img/lp-img/other-university.png') }}" alt="他大学でログインしたときの表示">
            </div>
            <p class="lp-compare-caption">
              <span class="lp-compare-hl other-hl">「東洋大学専用」</span>の板だけが表示されます
            </p>
          </div>
        </div>

        <div class="lp-compare-note">
          <span class="lp-compare-note-icon">🔐</span>
          <p>
            <strong>「◯◯大学専用」と表示されている板は、他大学の学生には一切見えません。</strong>
            同じ大学の仲間だけが集まる、完全に独立したコミュニティです。<br>
            一方で「全国の大学生と交流しよう！」の板は、どの大学の学生にも共通で表示され、自由に交流できます。
          </p>
        </div>
      </div>
    </div>
  </section>

  {{-- カテゴリ一覧 --}}
  <section class="lp-section lp-categories">
    <div class="lp-section-inner">
      <div class="lp-section-head lp-fade">
        <span class="lp-section-eyebrow">CATEGORIES</span>
        <h2 class="lp-section-title">話題は、あらゆる方面をカバー。</h2>
        <p class="lp-section-lead">
          就活・講義情報・趣味の共有から、一人暮らしの不安・恋愛相談まで。<br>
          大学生のリアルな日常に寄り添うカテゴリを、たっぷりご用意しています。
        </p>
        <div class="lp-cat-stats lp-fade">
          <div class="lp-cat-stat">
            <div class="num">20+</div>
            <div class="label">メジャーカテゴリ</div>
          </div>
          <div class="lp-cat-stat">
            <div class="num">130+</div>
            <div class="label">サブジャンル</div>
          </div>
          <div class="lp-cat-stat">
            <div class="num">2</div>
            <div class="label">交流エリア（自大学／全国）</div>
          </div>
        </div>
      </div>

      {{-- タブ切り替え（CSSのみ） --}}
      <div class="lp-cat-tabs-wrap lp-fade">
        <input type="radio" name="lp-cat-tab" id="lp-cat-own-tab" class="lp-cat-radio" checked>
        <input type="radio" name="lp-cat-tab" id="lp-cat-all-tab" class="lp-cat-radio">

        <div class="lp-cat-tabs">
          <label for="lp-cat-own-tab" class="lp-cat-tab own">
            <span class="lp-cat-tab-mark"></span>
            <span class="lp-cat-tab-text">
              <strong>自分の大学専用板</strong>
              <small>同じキャンパスの仲間だけ</small>
            </span>
          </label>
          <label for="lp-cat-all-tab" class="lp-cat-tab all">
            <span class="lp-cat-tab-mark"></span>
            <span class="lp-cat-tab-text">
              <strong>全国の大学生と交流</strong>
              <small>他大学の学生とも繋がれる</small>
            </span>
          </label>
        </div>

        {{-- パネル: 自大学専用 --}}
        <div class="lp-cat-panel lp-cat-panel-own">
          <p class="lp-cat-panel-note">
            💡 <strong>「◯◯大学専用」と書かれたカテゴリは、同じ大学の学生にしか表示されません。</strong>
            身内だけだから話せる、ディープでリアルな情報がここに集まります。
          </p>

          <div class="lp-cat-groups">
            <details class="lp-cat-group">
              <summary>📚 授業・履修 <span class="lp-cat-count">9</span></summary>
              <div class="lp-cat-tags">
                <span>楽単・鬼単情報</span><span>テスト・試験対策</span><span>ゼミ・研究室選び</span><span>文系学部</span><span>理系学部</span><span>医療系学部</span><span>資格・留学</span><span>教授・教員の話</span>
              </div>
            </details>
            <details class="lp-cat-group">
              <summary>🎉 サークル・交流 <span class="lp-cat-count">7</span></summary>
              <div class="lp-cat-tags">
                <span>サークル・部活(体)</span><span>サークル・部活(文)</span><span>イベント・学祭</span><span>PC・スマホ・ガジェット</span><span>ゲーム・趣味</span><span>オタ活</span><span>恋愛相談</span>
              </div>
            </details>
            <details class="lp-cat-group">
              <summary>💬 雑談・その他 <span class="lp-cat-count">5</span></summary>
              <div class="lp-cat-tags">
                <span>大学雑談・ニュース</span><span>なんでも雑談</span><span>なんでも実況</span><span>仮面浪人・編入・留年</span><span>愚痴・吐き出し</span>
              </div>
            </details>
            <details class="lp-cat-group">
              <summary>💼 就活・進路 <span class="lp-cat-count">5</span></summary>
              <div class="lp-cat-tags">
                <span>進路・キャリア相談</span><span>就活・インターン</span><span>就活・選考・ES</span><span>院試・進学</span><span>公務員・教員採用</span>
              </div>
            </details>
            <details class="lp-cat-group">
              <summary>🏠 暮らし・生活 <span class="lp-cat-count">8</span></summary>
              <div class="lp-cat-tags">
                <span>新入生・質問</span><span>学食・周辺グルメ</span><span>一人暮らし・住まい</span><span>自炊・料理・レシピ</span><span>ファッション</span><span>雪・天気・交通</span><span>古本・教科書譲渡</span><span>落とし物・困りごと</span>
              </div>
            </details>
          </div>
        </div>

        {{-- パネル: 全国板 --}}
        <div class="lp-cat-panel lp-cat-panel-all">
          <p class="lp-cat-panel-note all">
            🌏 <strong>全国約800校の大学生と共通で利用できるカテゴリです。</strong>
            どの大学にログインしても同じジャンルが表示され、他大学の学生と自由に交流できます。
          </p>

          <div class="lp-cat-groups">
            <details class="lp-cat-group">
              <summary>📢 運営・お知らせ <span class="lp-cat-count">3</span></summary>
              <div class="lp-cat-tags">
                <span>運営からのお知らせ</span><span>不具合報告・要望</span><span>削除依頼・通報窓口</span>
              </div>
            </details>
            <details class="lp-cat-group">
              <summary>💭 悩み・相談 <span class="lp-cat-count">8</span></summary>
              <div class="lp-cat-tags">
                <span>心の悩み</span><span>友達・人間関係</span><span>恋愛・失恋相談</span><span>休学・留年・単位</span><span>新入生の悩み・質問</span><span>一人暮らしの不安</span><span>健康・体調管理</span><span>奨学金・生活費</span>
              </div>
            </details>
            <details class="lp-cat-group">
              <summary>💼 就活・進路 <span class="lp-cat-count">19</span></summary>
              <div class="lp-cat-tags">
                <span>就活総合</span><span>27卒・28卒専用</span><span>インターン・早期選考</span><span>ES・面接・GD対策</span><span>IT・Web・通信・ゲーム</span><span>金融・商社・保険</span><span>メーカー(食品・消費財)</span><span>メーカー(自動車・機械)</span><span>マスコミ・広告・出版</span><span>コンサル・シンクタンク</span><span>建設・不動産・住宅</span><span>インフラ(鉄道/航空/電力)</span><span>流通・小売・人材・サービス</span><span>医療・製薬・バイオ</span><span>公務員・教員採用</span><span>大学院・研究室進学</span><span>資格・検定・TOEIC</span><span>留学・海外キャリア</span><span>起業・スタートアップ</span>
              </div>
            </details>
            <details class="lp-cat-group">
              <summary>📖 文系学問 <span class="lp-cat-count">6</span></summary>
              <div class="lp-cat-tags">
                <span>法学・政治学</span><span>経済学・経営学</span><span>文学・人文学</span><span>教育学・教職</span><span>外国語・言語学</span><span>社会学・福祉</span>
              </div>
            </details>
            <details class="lp-cat-group">
              <summary>🔬 理系学問 <span class="lp-cat-count">6</span></summary>
              <div class="lp-cat-tags">
                <span>数学・物理学</span><span>化学・生物学</span><span>工学・機械・電気</span><span>情報科学・CS</span><span>医学・薬学・看護</span><span>農学・環境科学</span>
              </div>
            </details>
            <details class="lp-cat-group">
              <summary>🏠 暮らし・生活 <span class="lp-cat-count">3</span></summary>
              <div class="lp-cat-tags">
                <span>一人暮らし・生活</span><span>アルバイト・パート</span><span>マネー・ポイ活・節約</span>
              </div>
            </details>
            <details class="lp-cat-group">
              <summary>👗 ファッション・美容 <span class="lp-cat-count">5</span></summary>
              <div class="lp-cat-tags">
                <span>メンズファッション</span><span>レディースファッション</span><span>美容・コスメ・メイク</span><span>香水・ヘアスタイル</span><span>筋トレ・ボディメイク</span>
              </div>
            </details>
            <details class="lp-cat-group">
              <summary>⚽ 趣味・スポーツ <span class="lp-cat-count">6</span></summary>
              <div class="lp-cat-tags">
                <span>野球ch</span><span>サッカーch</span><span>サウナ・温泉・旅行</span><span>カメラ・写真</span><span>キャンプ・アウトドア</span><span>ボランティア・NPO</span>
              </div>
            </details>
            <details class="lp-cat-group">
              <summary>🍜 グルメ・料理 <span class="lp-cat-count">6</span></summary>
              <div class="lp-cat-tags">
                <span>ラーメン・麺類</span><span>カフェ・スイーツ</span><span>自炊・レシピ・弁当</span><span>学食・ランチ・外食</span><span>コンビニ・ファストフード</span><span>お酒・居酒屋</span>
              </div>
            </details>
            <details class="lp-cat-group">
              <summary>🎮 エンタメ・ゲーム <span class="lp-cat-count">10</span></summary>
              <div class="lp-cat-tags">
                <span>スマホゲ・ソシャゲ</span><span>ポケモン・任天堂</span><span>FPS・PCゲーム・eスポ</span><span>アニメ・漫画・声優</span><span>Vtuber・配信者</span><span>音楽・邦ロック</span><span>アイドル(坂道/K-POP)</span><span>映画・ドラマ・Netflix</span><span>本・小説・読書</span><span>芸術・アート</span>
              </div>
            </details>
            <details class="lp-cat-group">
              <summary>💻 PC・デジタル <span class="lp-cat-count">4</span></summary>
              <div class="lp-cat-tags">
                <span>iPhone・スマホ</span><span>PC・ガジェット</span><span>プログラミング・技術</span><span>生成AI・ChatGPT</span>
              </div>
            </details>
            <details class="lp-cat-group">
              <summary>🚗 乗り物・交通 <span class="lp-cat-count">5</span></summary>
              <div class="lp-cat-tags">
                <span>自動車・ドライブ</span><span>バイク・ツーリング</span><span>自転車</span><span>免許・教習所</span><span>鉄道・飛行機・旅行</span>
              </div>
            </details>
            <details class="lp-cat-group">
              <summary>📍 地域・地元 <span class="lp-cat-count">7</span></summary>
              <div class="lp-cat-tags">
                <span>北海道・東北</span><span>関東・甲信越</span><span>北陸・東海</span><span>関西・近畿</span><span>中国・四国</span><span>九州・沖縄</span><span>海外・インターナショナル</span>
              </div>
            </details>
            <details class="lp-cat-group">
              <summary>📰 ニュース・情勢 <span class="lp-cat-count">3</span></summary>
              <div class="lp-cat-tags">
                <span>ニュース速報＋</span><span>気象・災害情報</span><span>世界情勢・政治</span>
              </div>
            </details>
            <details class="lp-cat-group">
              <summary>💬 雑談・その他 <span class="lp-cat-count">3</span></summary>
              <div class="lp-cat-tags">
                <span>総合雑談・実況</span><span>大学生活</span><span>深夜の雑談</span>
              </div>
            </details>
          </div>
        </div>
      </div>

      <p class="lp-cat-footnote lp-fade">
        ※ カテゴリは随時追加・調整されます。上記は代表例です。
      </p>
    </div>
  </section>

  {{-- 使い方 --}}
  <section class="lp-section">
    <div class="lp-section-inner">
      <div class="lp-section-head lp-fade">
        <span class="lp-section-eyebrow">HOW TO USE</span>
        <h2 class="lp-section-title">はじめかたは、とってもカンタン。</h2>
        <p class="lp-section-lead">新規登録からスレッド作成・書き込みまでの流れをご紹介。最短3分で仲間の輪に入れます。</p>
      </div>

      <div class="lp-flow">
        {{-- STEP 1 --}}
        <div class="lp-flow-step lp-fade">
          <div class="lp-flow-img"><img src="{{ asset('img/lp-img/university.jpg') }}" alt="大学一覧検索画面"></div>
          <div class="lp-flow-num">1</div>
          <div class="lp-flow-content">
            <h4>対応大学一覧から自分の大学を探す</h4>
            <p>
              新規登録画面から「対応大学一覧」を開き、約800校の中から自分の大学が対応しているか確認します。
              大学名・ドメインで検索可能。
            </p>
            <span class="lp-flow-note">💡 見つからない場合は「大学追加リクエスト」から申請OK</span>
          </div>
        </div>

        {{-- STEP 2 --}}
        <div class="lp-flow-step alt lp-fade">
          <div class="lp-flow-num">2</div>
          <div class="lp-flow-content">
            <h4>大学メールアドレスでアカウント登録</h4>
            <p>
              対応していたら、大学が発行するメールアドレスを使って登録。
              ニックネーム・パスワードを入力し、届いた認証メールから本登録を完了させます。
            </p>
            <span class="lp-flow-note">🔒 大学生であることが確認されるから安心</span>
          </div>
          <div class="lp-flow-img"><img src="{{ asset('img/lp-img/register.jpg') }}" alt="アカウント登録画面"></div>
        </div>

        {{-- STEP 3 --}}
        <div class="lp-flow-step lp-fade">
          <div class="lp-flow-img"><img src="{{ asset('img/lp-img/cateogory-1.jpg') }}" alt="ジャンル一覧画面"></div>
          <div class="lp-flow-num">3</div>
          <div class="lp-flow-content">
            <h4>ジャンル一覧からカテゴリを選択</h4>
            <p>
              ログイン後、「自分の大学専用板」または「全国の大学生と交流」から興味のあるジャンルを選択。
              楽単情報・就活・恋愛相談など、話題は自由に選べます。
            </p>
          </div>
        </div>

        {{-- STEP 4 --}}
        <div class="lp-flow-step alt lp-fade">
          <div class="lp-flow-num">4</div>
          <div class="lp-flow-content">
            <h4>画面右下のアイコンをクリック</h4>
            <p>
              スレッド一覧の画面<strong>右下に表示される作成アイコン</strong>をタップ。
              画像の赤丸の部分がスレッド作成ボタンです。
            </p>
            <span class="lp-flow-note accent">👉 赤丸の位置のアイコンをクリック！</span>
          </div>
          <div class="lp-flow-img"><img src="{{ asset('img/lp-img/create-img.png') }}" alt="スレッド作成ボタンの位置を示す画面"></div>
        </div>

        {{-- STEP 5 --}}
        <div class="lp-flow-step lp-fade">
          <div class="lp-flow-img"><img src="{{ asset('img/lp-img/create.jpg') }}" alt="スレッド作成モーダル"></div>
          <div class="lp-flow-num">5</div>
          <div class="lp-flow-content">
            <h4>スレッドを作成する</h4>
            <p>
              作成モーダルが開いたら、タイトルと本文を入力して「作成する」をタップ。
              これであなたのスレッドが誕生し、仲間からの返信を待つ状態に。
            </p>
            <span class="lp-flow-note">✏️ NGワードフィルタで投稿も安心</span>
          </div>
        </div>

        {{-- STEP 6 --}}
        <div class="lp-flow-step alt lp-fade">
          <div class="lp-flow-num">6</div>
          <div class="lp-flow-content">
            <h4>気になるスレッドに書き込んで交流</h4>
            <p>
              他のスレッドに返信（レス）を書き込んで、会話に参加しましょう。
              いいね・メンション・購読機能で、あなたの大学生活がもっと楽しく、もっと深くなります。
            </p>
          </div>
          <div class="lp-flow-img"><img src="{{ asset('img/lp-img/res.jpg') }}" alt="返信書き込み画面"></div>
        </div>
      </div>
    </div>
  </section>

  {{-- 安心・安全 --}}
  <section class="lp-section lp-safety">
    <div class="lp-section-inner">
      <div class="lp-section-head lp-fade">
        <span class="lp-section-eyebrow lp-eyebrow-on-dark">SAFETY</span>
        <h2 class="lp-section-title">安心・安全への取り組み</h2>
        <p class="lp-section-lead">健全なキャンパスコミュニティを守るため、多層的な対策を実施しています。</p>
      </div>
      <div class="lp-safety-grid">
        <div class="lp-safety-item lp-fade">
          <div class="lp-safety-icon">✉️</div>
          <div>
            <h4>大学メール認証</h4>
            <p>大学が発行する公式メールアドレスでのみ登録可能。なりすましや部外者の侵入を防止します。</p>
          </div>
        </div>
        <div class="lp-safety-item lp-fade">
          <div class="lp-safety-icon">🚫</div>
          <div>
            <h4>NGワードフィルタ</h4>
            <p>不適切な単語を含む投稿は自動でブロック。誹謗中傷や差別発言を未然に防ぎます。</p>
          </div>
        </div>
        <div class="lp-safety-item lp-fade">
          <div class="lp-safety-icon">⚠️</div>
          <div>
            <h4>通報機能</h4>
            <p>気になる投稿はワンタップで通報可能。運営が迅速に確認し、必要に応じて削除します。</p>
          </div>
        </div>
        <div class="lp-safety-item lp-fade">
          <div class="lp-safety-icon">🛡️</div>
          <div>
            <h4>完全匿名表示</h4>
            <p>投稿にはニックネームとIDのみ表示。個人が特定される情報は一切公開されません。</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- FAQ --}}
  <section class="lp-section">
    <div class="lp-section-inner">
      <div class="lp-section-head lp-fade">
        <span class="lp-section-eyebrow">FAQ</span>
        <h2 class="lp-section-title">よくあるご質問</h2>
      </div>
      <div class="lp-faq-list">
        <details class="lp-faq-item lp-fade">
          <summary>本当に無料で使えますか？</summary>
          <p>はい、すべての機能を無料でお使いいただけます。登録料・月額利用料は一切かかりません。</p>
        </details>
        <details class="lp-faq-item lp-fade">
          <summary>自分の大学が一覧にありません。</summary>
          <p>「大学追加リクエスト」ページから申請していただければ、順次対応させていただきます。</p>
        </details>
        <details class="lp-faq-item lp-fade">
          <summary>個人が特定されることはありませんか？</summary>
          <p>投稿時にはニックネームとランダムなIDのみが表示されます。メールアドレスや本名が他のユーザーに見えることはありません。</p>
        </details>
        <details class="lp-faq-item lp-fade">
          <summary>卒業した後も使えますか？</summary>
          <p>登録時に認証した大学メールが有効な間はご利用いただけます。卒業後の利用方針は今後お知らせします。</p>
        </details>
        <details class="lp-faq-item lp-fade">
          <summary>退会はできますか？</summary>
          <p>ユーザー設定からいつでもアカウント削除のお申し込みが可能です。</p>
        </details>
      </div>
    </div>
  </section>

  {{-- 最終CTA --}}
  <section class="lp-section lp-final-cta">
    <div class="lp-section-inner lp-fade">
      <h2>さあ、キャンパスライフを<br>もっと豊かにしよう。</h2>
      <p>同じ大学の仲間が、あなたの投稿を待っています。<br>登録はたったの3分。今すぐはじめよう。</p>
      <div class="lp-final-cta-buttons">
        <a href="{{ route('register') }}" class="lp-btn lp-btn-filled lp-btn-lg">無料で新規登録する</a>
        <a href="{{ route('login') }}" class="lp-btn lp-btn-outline lp-btn-lg">ログインはこちら</a>
      </div>
    </div>
  </section>

</div>

<script>
  // フェードインアニメーション
  (function() {
    const items = document.querySelectorAll('.lp-fade');
    if (!('IntersectionObserver' in window)) {
      items.forEach(el => el.classList.add('is-visible'));
      return;
    }
    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          io.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.15
    });
    items.forEach(el => io.observe(el));
  })();
</script>
@endsection