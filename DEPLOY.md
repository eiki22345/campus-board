# hokkai-board デプロイ手順書

> 最終更新: 2026-04-02

---

## 前提条件

| 項目 | 内容 |
|------|------|
| フレームワーク | Laravel 12 + Filament 3 |
| PHP | 8.2 以上 |
| フロントエンド | Vite + Alpine.js + Tailwind CSS + Bootstrap 5 |
| WebSocket | Laravel Reverb |
| キュー | database ドライバ |
| 現在のDB | SQLite（本番は MySQL に移行） |
| ホスティング | XServer VPS |
| CDN/DNS | Cloudflare |

---

## Phase 0 : 事前準備（ローカルで決めること）

### 0-1. ドメイン取得

- Cloudflare Registrar / お名前.com 等で取得
- 以降 `student-bbs-campus-board.com` として記載

### 0-2. メールサービス決定

| サービス | 無料枠 | 備考 |
|----------|--------|------|
| **Resend** | 月3,000通 | Laravel対応パッケージあり。小規模なら十分 |
| Amazon SES | 月62,000通（EC2経由） | 設定やや複雑 |
| Mailgun | 月1,000通（30日間） | 期限後は有料 |

**推奨: Resend**（設定が簡単、無料枠で十分）

### 0-3. 管理者アカウント情報

本番用の値を決めておく：

```
ADMIN_NICKNAME=admin
ADMIN_EMAIL=admin@student-bbs-campus-board.com
ADMIN_PASSWORD=（強力なパスワード）
```

### 0-4. NGワード設定ファイル

```bash
cp config/ng_words.php.example config/ng_words.php
```

本番運用では `NgWordSeeder` でDBに投入される。`config/ng_words.php` にも必要に応じて追記。

---

## Phase 1 : XServer VPS 契約 & 初期設定

### 1-1. VPS契約

- **プラン**: メモリ2GB（月830円〜）
- **OS**: Ubuntu 24.04 LTS
- 契約後に固定IPアドレスが付与される

### 1-2. SSH初期設定

```bash
# ローカルPCから接続
ssh root@YOUR_VPS_IP

# 一般ユーザー作成（rootでの運用は避ける）
adduser deploy
usermod -aG sudo deploy

# SSH鍵認証の設定
su - deploy
mkdir -p ~/.ssh
chmod 700 ~/.ssh
# ローカルの公開鍵を ~/.ssh/authorized_keys に追加
nano ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys

# rootログイン無効化 & パスワード認証無効化
sudo nano /etc/ssh/sshd_config
# PermitRootLogin no
# PasswordAuthentication no
sudo systemctl restart sshd
```

### 1-3. ファイアウォール設定

```bash
sudo ufw allow 22/tcp    # SSH
sudo ufw allow 80/tcp    # HTTP
sudo ufw allow 443/tcp   # HTTPS
# 8080 は Nginx がリバースプロキシするため外部公開不要
sudo ufw enable
sudo ufw status
```

---

## Phase 2 : ソフトウェアインストール

### 2-1. システム更新

```bash
sudo apt update && sudo apt upgrade -y
```

### 2-2. Nginx

```bash
sudo apt install nginx -y
sudo systemctl enable nginx
```

### 2-3. PHP 8.2 + 必要モジュール

```bash
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

sudo apt install php8.2-fpm php8.2-cli php8.2-mbstring php8.2-xml \
  php8.2-curl php8.2-zip php8.2-gd php8.2-mysql php8.2-sqlite3 \
  php8.2-bcmath php8.2-intl php8.2-readline php8.2-tokenizer -y

# バージョン確認
php -v
```

### 2-4. MySQL 8.0

```bash
sudo apt install mysql-server -y
sudo systemctl enable mysql

# 初期セキュリティ設定
sudo mysql_secure_installation

# DB & ユーザー作成
sudo mysql -u root -p
```

```sql
CREATE DATABASE hokkai_board CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'hokkai_user'@'localhost' IDENTIFIED BY '強力なパスワードをここに';
GRANT ALL PRIVILEGES ON hokkai_board.* TO 'hokkai_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 2-5. Composer

```bash
cd /tmp
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer --version
```

### 2-6. Node.js (LTS)

```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install nodejs -y
node -v
npm -v
```

### 2-7. Supervisor

```bash
sudo apt install supervisor -y
sudo systemctl enable supervisor
```

### 2-8. Git

```bash
sudo apt install git -y
```

---

## Phase 3 : Cloudflare 設定

### 3-1. アカウント作成 & ドメイン追加

1. https://dash.cloudflare.com でサインアップ
2. ドメインを追加 → Free プランを選択
3. 指示に従いネームサーバーをCloudflareに変更

### 3-2. DNS レコード

| タイプ | 名前 | 値 | プロキシ |
|--------|------|-----|---------|
| A | `@` | VPSのIPアドレス | プロキシ有効（橙色の雲） |
| A | `www` | VPSのIPアドレス | プロキシ有効 |

### 3-3. SSL/TLS 設定

- **SSL/TLS モード**: `Full (Strict)`
- **オリジン証明書発行**:
  1. Cloudflare ダッシュボード → SSL/TLS → Origin Server
  2. 「Create Certificate」をクリック
  3. 秘密鍵と証明書をダウンロード

```bash
# VPS上に証明書を配置
sudo mkdir -p /etc/ssl/cloudflare
sudo nano /etc/ssl/cloudflare/origin.pem      # 証明書を貼り付け
sudo nano /etc/ssl/cloudflare/origin-key.pem  # 秘密鍵を貼り付け
sudo chmod 600 /etc/ssl/cloudflare/origin-key.pem
```

### 3-4. キャッシュルール（推奨）

Cloudflare ダッシュボード → Rules → Page Rules:

| URL パターン | 設定 |
|-------------|------|
| `student-bbs-campus-board.com/build/*` | Cache Level: Cache Everything |
| `student-bbs-campus-board.com/img/*` | Cache Level: Cache Everything |
| `student-bbs-campus-board.com/fonts/*` | Cache Level: Cache Everything |

### 3-5. WebSocket 対応

Cloudflare は WebSocket をデフォルトでプロキシ可能。  
Reverb の接続は Nginx リバースプロキシ経由で `wss://student-bbs-campus-board.com/app/` にルーティングする（Phase 5 で設定）。

---

## Phase 4 : プロジェクトデプロイ

### 4-1. ディレクトリ準備

```bash
sudo mkdir -p /var/www/hokkai-board
sudo chown deploy:deploy /var/www/hokkai-board
```

### 4-2. Git Clone

```bash
cd /var/www/hokkai-board
git clone git@github.com:YOUR_USER/hokkai-board.git .
```

> GitHub に SSH鍵を登録しておくこと:
> ```bash
> ssh-keygen -t ed25519
> cat ~/.ssh/id_ed25519.pub
> # → GitHub の Settings > SSH Keys に追加
> ```

### 4-3. 本番 .env 作成

```bash
cp .env.example .env
nano .env
```

以下の内容に変更：

```env
APP_NAME="北海ボード"
APP_ENV=production
APP_KEY=  # ← 後で artisan key:generate で生成
APP_DEBUG=false
APP_URL=https://student-bbs-campus-board.com

APP_LOCALE=ja
APP_FALLBACK_LOCALE=ja
APP_FAKER_LOCALE=ja_JP

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=warning

# ---- MySQL ----
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hokkai_board
DB_USERNAME=hokkai_user
DB_PASSWORD=Phase2で設定したパスワード

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_PATH=/
SESSION_DOMAIN=student-bbs-campus-board.com
SESSION_SECURE_COOKIE=true

BROADCAST_CONNECTION=reverb
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

# ---- メール（Resend の場合） ----
MAIL_MAILER=resend
RESEND_KEY=re_xxxxxxxxxxxx
MAIL_FROM_ADDRESS=noreply@student-bbs-campus-board.com
MAIL_FROM_NAME="${APP_NAME}"

# ---- Reverb (WebSocket) ----
REVERB_APP_ID=（ランダム数値）
REVERB_APP_KEY=（ランダム文字列）
REVERB_APP_SECRET=（ランダム文字列）
REVERB_HOST=student-bbs-campus-board.com
REVERB_PORT=443
REVERB_SCHEME=https

REVERB_SERVER_HOST=127.0.0.1
REVERB_SERVER_PORT=8080

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"

VITE_APP_NAME="${APP_NAME}"

# ---- 管理者 ----
ADMIN_NICKNAME=admin
ADMIN_EMAIL=admin@student-bbs-campus-board.com
ADMIN_PASSWORD=強力なパスワード

# ---- HashID ----
HASH_ID_SECRET=ランダムな秘密鍵
```

> **Reverb のキー生成方法**:
> ```bash
> php -r "echo bin2hex(random_bytes(10));" # APP_KEY用
> php -r "echo bin2hex(random_bytes(10));" # APP_SECRET用
> php -r "echo random_int(100000, 999999);" # APP_ID用
> ```

### 4-4. Composer & npm インストール

```bash
composer install --no-dev --optimize-autoloader

npm ci
npm run build
```

### 4-5. Laravel セットアップ

```bash
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 4-6. パーミッション設定

```bash
sudo chown -R deploy:www-data /var/www/hokkai-board
sudo chmod -R 755 /var/www/hokkai-board
sudo chmod -R 775 /var/www/hokkai-board/storage
sudo chmod -R 775 /var/www/hokkai-board/bootstrap/cache

# アップロード画像用
sudo chmod -R 775 /var/www/hokkai-board/storage/app/public
```

---

## Phase 5 : Nginx 設定

### 5-1. サイト設定ファイル作成

```bash
sudo nano /etc/nginx/sites-available/hokkai-board
```

```nginx
server {
    listen 80;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name student-bbs-campus-board.com www.student-bbs-campus-board.com;

    # Cloudflare Origin 証明書
    ssl_certificate     /etc/ssl/cloudflare/origin.pem;
    ssl_certificate_key /etc/ssl/cloudflare/origin-key.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;

    root /var/www/hokkai-board/public;
    index index.php;

    # ログ
    access_log /var/log/nginx/hokkai-board-access.log;
    error_log  /var/log/nginx/hokkai-board-error.log;

    # ファイルアップロード上限
    client_max_body_size 10M;

    # 静的ファイルのキャッシュ
    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    # Vite ビルドファイル
    location /build/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    # Laravel メインルーティング
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # .ht ファイルへのアクセス拒否
    location ~ /\.ht {
        deny all;
    }

    # .env ファイルへのアクセス拒否
    location ~ /\.env {
        deny all;
    }

    # Reverb WebSocket リバースプロキシ
    location /app/ {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;

        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;

        proxy_read_timeout 60s;
        proxy_send_timeout 60s;
    }

    # Reverb API（サーバー間通信）
    location /apps/ {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;

        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

### 5-2. 有効化 & テスト

```bash
sudo ln -s /etc/nginx/sites-available/hokkai-board /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default  # デフォルト設定を削除
sudo nginx -t
sudo systemctl reload nginx
```

---

## Phase 6 : Supervisor 設定（Reverb + キューワーカー）

### 6-1. キューワーカー

```bash
sudo nano /etc/supervisor/conf.d/hokkai-queue.conf
```

```ini
[program:hokkai-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/hokkai-board/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=deploy
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/supervisor/hokkai-queue.log
stopwaitsecs=3600
```

### 6-2. Reverb WebSocket サーバー

```bash
sudo nano /etc/supervisor/conf.d/hokkai-reverb.conf
```

```ini
[program:hokkai-reverb]
process_name=%(program_name)s
command=php /var/www/hokkai-board/artisan reverb:start --host=127.0.0.1 --port=8080
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=deploy
numprocs=1
redirect_stderr=true
stdout_logfile=/var/log/supervisor/hokkai-reverb.log
stopwaitsecs=10
```

### 6-3. Supervisor 起動

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start all

# 状態確認
sudo supervisorctl status
```

---

## Phase 7 : Reverb `allowed_origins` の本番修正

### 7-1. config/reverb.php を修正

`allowed_origins` を `['*']` から自ドメインに限定する：

```php
// config/reverb.php 内の 'allowed_origins' を変更
'allowed_origins' => [
    env('APP_URL', 'https://student-bbs-campus-board.com'),
],
```

または `.env` で制御したい場合：

```php
'allowed_origins' => explode(',', env('REVERB_ALLOWED_ORIGINS', '*')),
```

```env
# .env
REVERB_ALLOWED_ORIGINS=https://student-bbs-campus-board.com,https://www.student-bbs-campus-board.com
```

---

## Phase 8 : GitHub Actions（テスト自動化）

### 8-1. ワークフローファイル作成

`.github/workflows/test.yml` をリポジトリに追加：

```yaml
name: Tests

on:
  pull_request:
    branches: [main]
  push:
    branches: [main]

jobs:
  test:
    runs-on: ubuntu-latest

    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ROOT_PASSWORD: password
          MYSQL_DATABASE: hokkai_board_test
        ports:
          - 3306:3306
        options: >-
          --health-cmd="mysqladmin ping"
          --health-interval=10s
          --health-timeout=5s
          --health-retries=5

    steps:
      - uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          extensions: mbstring, xml, curl, zip, gd, mysql, bcmath, intl
          coverage: none

      - name: Setup Node.js
        uses: actions/setup-node@v4
        with:
          node-version: '20'
          cache: 'npm'

      - name: Cache Composer packages
        uses: actions/cache@v4
        with:
          path: vendor
          key: ${{ runner.os }}-composer-${{ hashFiles('composer.lock') }}
          restore-keys: ${{ runner.os }}-composer-

      - name: Install Composer dependencies
        run: composer install --no-interaction --prefer-dist

      - name: Install npm dependencies
        run: npm ci

      - name: Build frontend
        run: npm run build

      - name: Prepare environment
        run: |
          cp .env.example .env
          php artisan key:generate

      - name: Configure test database
        run: |
          echo "DB_CONNECTION=mysql" >> .env
          echo "DB_HOST=127.0.0.1" >> .env
          echo "DB_PORT=3306" >> .env
          echo "DB_DATABASE=hokkai_board_test" >> .env
          echo "DB_USERNAME=root" >> .env
          echo "DB_PASSWORD=password" >> .env

      - name: Copy ng_words config
        run: cp config/ng_words.php.example config/ng_words.php

      - name: Run migrations
        run: php artisan migrate --force

      - name: Run tests
        run: php artisan test
```

---

## Phase 9 : デプロイ運用（更新手順）

### 9-1. 手動デプロイスクリプト

```bash
sudo nano /var/www/hokkai-board/deploy.sh
chmod +x /var/www/hokkai-board/deploy.sh
```

```bash
#!/bin/bash
set -e

cd /var/www/hokkai-board

echo "==> メンテナンスモード ON"
php artisan down

echo "==> 最新コードを取得"
git pull origin main

echo "==> Composer 更新"
composer install --no-dev --optimize-autoloader --no-interaction

echo "==> npm ビルド"
npm ci
npm run build

echo "==> マイグレーション"
php artisan migrate --force

echo "==> キャッシュクリア & 再生成"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "==> キュー再起動"
php artisan queue:restart

echo "==> Reverb 再起動"
sudo supervisorctl restart hokkai-reverb

echo "==> メンテナンスモード OFF"
php artisan up

echo "✅ デプロイ完了"
```

実行：

```bash
cd /var/www/hokkai-board && bash deploy.sh
```

---

## チェックリスト

### デプロイ前

- [ ] ドメイン取得済み
- [ ] XServer VPS 契約済み
- [ ] Cloudflare アカウント作成済み
- [ ] メールサービス決定（Resend 推奨）
- [ ] `ADMIN_EMAIL` / `ADMIN_PASSWORD` 決定
- [ ] `HASH_ID_SECRET` 生成
- [ ] `config/ng_words.php` 作成

### サーバーセットアップ

- [ ] SSH鍵認証 & rootログイン無効化
- [ ] ファイアウォール設定（22, 80, 443）
- [ ] Nginx インストール & 設定
- [ ] PHP 8.2 + 拡張モジュール
- [ ] MySQL 8.0 + DB作成
- [ ] Composer, Node.js, Supervisor, Git

### Cloudflare

- [ ] DNS Aレコード設定
- [ ] SSL/TLS → Full (Strict)
- [ ] Origin 証明書発行 & VPSに配置

### アプリケーション

- [ ] `git clone` 完了
- [ ] `.env` 設定完了
- [ ] `composer install` 完了
- [ ] `npm run build` 完了
- [ ] `php artisan migrate --force` 成功
- [ ] `php artisan db:seed --force` 成功
- [ ] `php artisan storage:link` 実行
- [ ] キャッシュ生成（config, route, view, event）
- [ ] パーミッション設定

### プロセス管理

- [ ] キューワーカー（Supervisor）起動確認
- [ ] Reverb WebSocket（Supervisor）起動確認
- [ ] `supervisorctl status` で全プロセス RUNNING

### セキュリティ

- [ ] `APP_DEBUG=false` 確認
- [ ] `SESSION_SECURE_COOKIE=true` 確認
- [ ] `allowed_origins` を自ドメインに限定
- [ ] `.env` / `.ht*` へのアクセス拒否（Nginx）
- [ ] HTTPS リダイレクト動作確認
- [ ] Filament 管理画面へのアクセス制限確認

### 動作確認

- [ ] トップページ表示
- [ ] ユーザー登録 → メール認証
- [ ] ログイン → 掲示板閲覧
- [ ] スレッド作成・投稿
- [ ] WebSocket リアルタイム更新
- [ ] 画像アップロード
- [ ] Filament 管理画面（/admin）

---

## トラブルシューティング

### 503 エラー

```bash
# メンテナンスモード解除忘れ
php artisan up

# PHP-FPM 動作確認
sudo systemctl status php8.2-fpm
```

### 500 エラー

```bash
# ログ確認
tail -50 /var/www/hokkai-board/storage/logs/laravel.log
tail -50 /var/log/nginx/hokkai-board-error.log

# パーミッション再設定
sudo chown -R deploy:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### WebSocket 接続失敗

```bash
# Reverb プロセス確認
sudo supervisorctl status hokkai-reverb

# ポート確認
ss -tlnp | grep 8080

# ログ確認
tail -50 /var/log/supervisor/hokkai-reverb.log
```

### キューが処理されない

```bash
sudo supervisorctl status hokkai-queue
tail -50 /var/log/supervisor/hokkai-queue.log

# 手動テスト
php artisan queue:work --once
```
