<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. 大学マスタ (usersより先に必要)
        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 北海道大学
            $table->string('email_domain')->unique(); // @hokudai.ac.jp
            $table->timestamps();
        });

        // 2. 親カテゴリ (boardsより先に必要)
        Schema::create('major_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // グルメ、大学、就活
            $table->timestamps();
        });

        // 3. ユーザー (universitiesに依存)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nickname');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            // HOKKAI BOARD 独自カラム
            $table->foreignId('university_id')->constrained('universities');
            $table->integer('role')->default(0); // 0:学生, 1:管理者
            $table->string('icon_path')->nullable();

            // Stripe Cashier用
            $table->string('stripe_id')->nullable()->index();
            $table->string('pm_type')->nullable();
            $table->string('pm_last_four')->nullable();
            $table->timestamp('trial_ends_at')->nullable();

            $table->timestamps();
            $table->softDeletes(); // 論理削除
        });

        // 4. サブスクリプション (Stripe用)
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('type');
            $table->string('stripe_id')->unique();
            $table->string('stripe_status');
            $table->string('stripe_price')->nullable();
            $table->integer('quantity')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });

        // 5. 板 (Board)
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('major_category_id')->constrained('major_categories');
            $table->foreignId('university_id')->nullable()->constrained('universities'); // 共通板ならNull
            $table->timestamps();
        });

        // 6. スレッド
        Schema::create('threads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content'); // 本文
            $table->foreignId('board_id')->constrained('boards');
            $table->foreignId('user_id')->constrained('users');
            $table->string('image_path')->nullable(); // スレ主画像
            $table->string('ip_address')->nullable(); // IPアドレス

            $table->timestamps();
            $table->softDeletes();
        });

        // 7. レス (Post)
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thread_id')->constrained('threads');
            $table->foreignId('user_id')->constrained('users');
            $table->integer('post_number'); // スレ内連番
            $table->text('content');
            $table->string('image_path')->nullable();
            $table->integer('likes_count')->default(0); // いいね数キャッシュ
            $table->string('ip_address')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // 8. レスへのいいね (Post Like)
        Schema::create('post_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->timestamps();

            // 1人のユーザーは同じレスに1回しかいいねできない
            $table->unique(['user_id', 'post_id']);
        });

        // 9. スレッドへのいいね (Thread Like)
        Schema::create('thread_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('thread_id')->constrained('threads')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'thread_id']);
        });

        // 10. 閲覧履歴
        Schema::create('browsing_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('thread_id')->constrained('threads')->onDelete('cascade');
            $table->timestamp('accessed_at');

            // 履歴の重複を防ぐ（Update運用）
            $table->unique(['user_id', 'thread_id']);
        });

        // 11. 通報
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('thread_id')->nullable()->constrained('threads');
            $table->foreignId('post_id')->nullable()->constrained('posts');
            $table->text('reason');
            $table->timestamps();
        });

        // 12. ジョブキュー (非同期処理用・Laravel標準)
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 作成と逆順で削除
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('browsing_histories');
        Schema::dropIfExists('thread_likes');
        Schema::dropIfExists('post_likes');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('threads');
        Schema::dropIfExists('boards');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('users');
        Schema::dropIfExists('major_categories');
        Schema::dropIfExists('universities');
    }
};
