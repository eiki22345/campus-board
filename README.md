# Campus Board (HOKKAI BOARD)

**大学生による、大学生のための、安心・安全なクローズド掲示板**

Campus Boardは、大学発行のメールアドレス（`@ac.jp` 等）による認証を必須とした、大学生限定の匿名掲示板プラットフォームです。
既存のSNSの「繋がり疲れ」や、従来の匿名掲示板の「治安の悪さ」を解決し、同じ大学の学生同士が安心して本音で語り合える場所を提供します。

さらに、**大学内だけの交流にとどまらず、全国の大学生と情報交換や交流ができる「共通板」機能**も備えています。

![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Filament](https://img.shields.io/badge/Filament-v3-FZAC06?style=for-the-badge&logo=filament&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-000000?style=for-the-badge&logo=mysql&logoColor=white)

## 📖 プロジェクト概要

### 解決する課題
- **情報の非対称性**: 履修登録やサークル情報など、大学内部のリアルな情報が得にくい。
- **孤独感**: キャンパス内での繋がりが希薄化している。
- **SNS疲れ**: 特定されずに本音で話したいが、既存の匿名掲示板は治安が悪く使いづらい。

### コンセプト
「セキュリティと匿名性を両立させた、現代版BBS」。
大学メール認証により部外者を排除しつつ、ハッシュIDによる適度な匿名性を確保しています。
また、**「大学ごとのクローズドなコミュニティ」と「全国規模のオープンなコミュニティ」の両立**を実現しています。

## ✨ 主な機能

### ユーザー向け機能
- **大学メールアドレス認証**: 所属大学の学生のみが登録・閲覧可能。
- **2層構造の掲示板**:
  - **大学限定板**: 自大学の学生のみがアクセス可能なクローズド空間（授業、サークル、学内イベント）。
  - **全国共通板**: **大学の垣根を超えて、全国の大学生と交流できる空間**（就活、恋愛、アルバイト、グルメなど）。
- **スレッド・レス投稿**: 画像投稿、安価（メンション）機能対応。
- **検索機能**: スレッドタイトルや本文からの検索。
- **マイページ**: 自身の投稿履歴やお気に入りの確認。
- **有料サブスクリプション**: Stripe決済による広告非表示などのプレミアム機能（実装中）。

### 管理・安全性機能
- **Filament 管理画面**: ユーザー、投稿、大学情報の管理。
- **NGワードフィルター**: データベース管理された禁止用語による自動フィルタリング。
- **通報機能**: ユーザーからの報告に基づくコンテンツ管理。
- **論理削除**: 誤操作や監査に対応したデータ保全。

## 🛠 技術スタック

| Category | Technology |
| --- | --- |
| **Backend** | Laravel 11.x |
| **Frontend** | Blade, Tailwind CSS, Alpine.js |
| **Admin Panel** | FilamentPHP v3 |
| **Real-time** | Laravel Reverb (WebSocket) |
| **Database** | MySQL 8.0 |
| **Payment** | Stripe (Laravel Cashier) |
| **Environment** | Docker (Laravel Sail) / Local |

