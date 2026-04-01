<?php

use App\Models\Board;
use App\Models\Post;
use App\Models\Thread;
use App\Models\University;
use App\Models\User;

test('未ログインユーザーはダッシュボードにアクセスできない', function () {
    $response = $this->get(route('dashboard'));

    $response->assertRedirect(route('login'));
});

test('未ログインユーザーはマイページにアクセスできない', function () {
    $response = $this->get(route('mypage'));

    $response->assertRedirect(route('login'));
});

test('未ログインユーザーはスレッド一覧にアクセスできない', function () {
    $board = Board::factory()->create(['university_id' => null]);

    $response = $this->get(route('threads.index', $board));

    $response->assertRedirect(route('login'));
});


test('スレッドにいいねできる', function () {
    $user = User::factory()->create();
    $board = Board::factory()->forUniversity($user->university)->create();
    $thread = Thread::factory()->create(['board_id' => $board->id, 'user_id' => $user->id]);

    $response = $this->actingAs($user)->postJson(route('threads.like', $thread));

    $response->assertOk()->assertJsonFragment(['is_liked' => true]);
    $this->assertDatabaseHas('thread_likes', ['user_id' => $user->id, 'thread_id' => $thread->id]);
});

test('いいね済みのスレッドを再度押すといいねが外れる', function () {
    $user = User::factory()->create();
    $board = Board::factory()->forUniversity($user->university)->create();
    $thread = Thread::factory()->create(['board_id' => $board->id, 'user_id' => $user->id]);
    $thread->likes()->attach($user->id);

    $response = $this->actingAs($user)->postJson(route('threads.like', $thread));

    $response->assertOk()->assertJsonFragment(['is_liked' => false]);
    $this->assertDatabaseMissing('thread_likes', ['user_id' => $user->id, 'thread_id' => $thread->id]);
});

test('他大学のスレッドにはいいねできない', function () {
    $user = User::factory()->create();
    $otherUniversity = University::factory()->create();
    $board = Board::factory()->forUniversity($otherUniversity)->create();
    $thread = Thread::factory()->create(['board_id' => $board->id]);

    $response = $this->actingAs($user)->postJson(route('threads.like', $thread));

    $response->assertForbidden();
});

test('管理者は他大学のスレッド一覧を閲覧できる', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $otherUniversity = University::factory()->create();
    $board = Board::factory()->forUniversity($otherUniversity)->create();

    $response = $this->actingAs($admin)->get(route('threads.index', $board));

    $response->assertOk();
});

test('管理者は他人のスレッドを削除できる', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $otherUser = User::factory()->create();
    $board = Board::factory()->forUniversity($otherUser->university)->create();
    $thread = Thread::factory()->create(['board_id' => $board->id, 'user_id' => $otherUser->id]);

    $response = $this->actingAs($admin)->delete(route('threads.destroy', [$board, $thread]));

    $response->assertRedirect();
    $this->assertSoftDeleted('threads', ['id' => $thread->id]);
});

test('管理者は他人の投稿を削除できる', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $otherUser = User::factory()->create();
    $board = Board::factory()->forUniversity($otherUser->university)->create();
    $thread = Thread::factory()->create(['board_id' => $board->id, 'user_id' => $otherUser->id]);
    $post = Post::factory()->create(['thread_id' => $thread->id, 'user_id' => $otherUser->id]);

    $response = $this->actingAs($admin)->delete(route('posts.destroy', $post));

    $response->assertRedirect();
    $this->assertSoftDeleted('posts', ['id' => $post->id]);
});

test('管理者は他大学のスレッドに投稿できる', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $otherUniversity = University::factory()->create();
    $board = Board::factory()->forUniversity($otherUniversity)->create();
    $thread = Thread::factory()->create(['board_id' => $board->id]);

    $response = $this->actingAs($admin)->post(route('posts.store', $thread), [
        'content' => '管理者からの投稿です。',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('posts', [
        'content' => '管理者からの投稿です。',
        'thread_id' => $thread->id,
        'user_id' => $admin->id,
    ]);
});
