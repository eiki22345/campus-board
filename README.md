# Campus Board (HOKKAI BOARD)

**大学生による、大学生のための、安心・安全なクローズド掲示板**

Campus Boardは、大学発行のメールアドレス（`@ac.jp` 等）による認証を必須とした、大学生限定の匿名掲示板プラットフォームです。
既存のSNSの「繋がり疲れ」や、従来の匿名掲示板の「治安の悪さ」を解決し、同じ大学の学生同士が安心して本音で語り合える場を提供します。

さらに、**大学内だけの交流にとどまらず、全国の大学生と情報交換や交流ができる「共通板」機能**も備えています。

## 🔗 公式サイト / Landing Page

サービスの概要・特徴・お問い合わせは公式LPからご覧いただけます。

👉 **[https://student-bbs-campus-board.com/](https://student-bbs-campus-board.com/)**



![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Filament](https://img.shields.io/badge/Filament-v3-F59E0B?style=for-the-badge&logo=filament&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![Tailwind](https://img.shields.io/badge/Tailwind-3-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

## 📖 プロジェクト概要

### 開発の背景
Facebook が当初ハーバード大学の学生限定の掲示板としてスタートし、そこから世界的サービスへと成長したように、「自分の大学の学生だけが集まる専用の場」には強い需要があります。
実際、有名大学には大学ごとの匿名掲示板サービスが存在し、根強い人気を誇っています。
一方で、**マイナーな大学にはそうした専用掲示板がほとんど存在せず**、X（旧 Twitter）などで発信された大学内のリアルな情報はタイムラインに流れて埋もれてしまいます。

Campus Board は、こうした状況に対して以下の価値を提供することを目指しています。

- **すべての大学に「自校専用の掲示板」を提供する**こと（規模の大小を問わず）。
- 同時に、**全国の大学生が大学の垣根を越えて交流できる共通の場**を用意すること。

「クローズドな自校コミュニティ」と「オープンな全国コミュニティ」の両方を一つのプラットフォームで両立させる点が、本サービスの最大の特徴です。

### 解決する課題
- **情報の非対称性**: 履修登録やサークル情報など、大学内部のリアルな情報が得にくい。特にマイナー大学では情報源そのものが乏しい。
- **情報の流動性**: X などのオープン SNS では大学関連の情報がタイムラインに流れてしまい、後から参照しづらい。
- **孤独感**: キャンパス内での繋がりが希薄化している。
- **SNS疲れ**: 特定されずに本音で話したいが、既存の匿名掲示板は治安が悪く使いづらい。

### コンセプト
「セキュリティと匿名性を両立させた、現代版BBS」。
大学メール認証により部外者を排除しつつ、ハッシュIDによる適度な匿名性を確保しています。
また、**「大学ごとのクローズドなコミュニティ」と「全国規模のオープンなコミュニティ」の両立**を実現しています。

## ✨ 主な機能

### ユーザー向け機能
- **大学メールアドレス認証**: `ac.jp` ドメインなど、所属大学の学生のみが登録・閲覧可能。
- **2層構造の掲示板**:
  - **大学限定板**: 自大学の学生のみがアクセスできるクローズド空間（授業、サークル、学内イベントなど）。
  - **全国共通板**: 大学の垣根を超えて全国の大学生と交流できる空間（就活、恋愛、アルバイト、グルメなど）。
- **スレッド・レス投稿**: テキストベースの投稿に対応。
- **リアルタイム更新**: Laravel Reverb (WebSocket) による新着投稿のリアルタイム反映。
- **いいね機能**: スレッド・投稿それぞれに対するリアクション。
- **検索機能**: スレッドタイトルや本文からの検索。
- **マイページ**: 自身の投稿履歴・閲覧履歴・お気に入りの確認。
- **大学リクエスト**: 未登録の大学を学生からリクエスト可能（現在 **1,118 大学**のドメインを登録済み）。

### 管理・安全性機能
- **Filament 管理画面**: ユーザー、投稿、大学情報、通報などの統合管理。
- **NGワードフィルター**: DB 管理された禁止用語による投稿の自動フィルタリング。
- **Yahoo! テキスト解析連携**: 不適切表現の判定支援（`YahooTextAnalysisService`）。
- **通報機能**: ユーザーからの報告に基づくコンテンツモデレーション。
- **論理削除（SoftDeletes）**: 誤操作や監査に対応したデータ保全。
- **メール認証 / パスワードリセット**: Resend 経由のトランザクションメール送信。

## 🛠 技術スタック

| Category | Technology |
| --- | --- |
| **Language** | PHP 8.2+ |
| **Backend Framework** | Laravel 12.x |
| **Auth (Scaffold)** | Laravel Breeze |
| **Admin Panel** | Filament PHP v3 |
| **Real-time** | Laravel Reverb (WebSocket) / Laravel Echo / pusher-js |
| **Frontend** | Blade, Bootstrap 5.3, Tailwind CSS 3, Alpine.js |
| **Build Tool** | Vite 7 |
| **Mail Delivery** | Resend (resend-laravel) |
| **External API** | Yahoo! テキスト解析 API |
| **Database** | MySQL 8.0 |
| **Testing** | Pest 3 (PHPUnit) |
| **Code Style** | Laravel Pint |
| **Environment** | XAMPP 等のローカル環境 |


##  ディレクトリ構成（抜粋）

```
app/
├── Filament/          # 管理画面（Pages / Resources / Widgets）
├── Http/              # Controllers / Middleware / Requests
├── Models/            # Board, Thread, Post, University, Region, Report ...
├── Notifications/     # メール通知（認証・パスワードリセット・通報結果）
├── Policies/          # Board / Thread / Post の認可ポリシー
├── Rules/             # NoInappropriateWords などのバリデーションルール
├── Services/          # YahooTextAnalysisService 等の外部サービス連携
└── Events/            # PostCreated（リアルタイム配信用）
```

## 🔐 セキュリティ・モデレーション方針

- 大学公式ドメイン（`ac.jp` 等）でのメール認証を必須化。
- ハッシュ化された匿名 ID により、特定リスクを抑えつつスレッド内での同一性を担保。
- NG ワードフィルター + Yahoo! テキスト解析による多層的な投稿チェック。
- Filament 管理画面からの通報処理、論理削除によるデータ保全。
