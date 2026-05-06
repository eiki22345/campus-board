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
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });


        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('region_id')->constrained('regions');
            $table->string('email_domain')->unique();
            $table->timestamps();
        });


        Schema::create('major_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });



        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nickname');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();


            $table->foreignId('university_id')->constrained('universities');
            $table->integer('role')->default(0);
            $table->string('icon_path')->nullable();


            $table->string('stripe_id')->nullable()->index();
            $table->string('pm_type')->nullable();
            $table->string('pm_last_four')->nullable();
            $table->timestamp('trial_ends_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });


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


        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('major_category_id')->constrained('major_categories');
            $table->foreignId('university_id')->nullable()->constrained('universities'); // 共通板ならNull
            $table->timestamps();
        });


        Schema::create('threads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->foreignId('board_id')->constrained('boards');
            $table->foreignId('user_id')->constrained('users');
            $table->string('image_path')->nullable();
            $table->string('ip_address')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thread_id')->constrained('threads');
            $table->foreignId('user_id')->constrained('users');
            $table->integer('post_number');
            $table->text('content');
            $table->string('image_path')->nullable();
            $table->integer('likes_count')->default(0);
            $table->string('ip_address')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('post_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->timestamps();


            $table->unique(['user_id', 'post_id']);
        });


        Schema::create('thread_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('thread_id')->constrained('threads')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'thread_id']);
        });


        Schema::create('browsing_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('thread_id')->constrained('threads')->onDelete('cascade');
            $table->timestamp('accessed_at');


            $table->unique(['user_id', 'thread_id']);
        });


        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('thread_id')->nullable()->constrained('threads');
            $table->foreignId('post_id')->nullable()->constrained('posts');
            $table->text('reason');
            $table->timestamps();
        });


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
        Schema::dropIfExists('regions');
    }
};
