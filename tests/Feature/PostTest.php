<?php

use App\Models\Board;
use App\Models\Post;
use App\Models\Thread;
use App\Models\University;
use App\Models\User;


test('投稿を作成できる', function () {
    $user = User::factory()->create();
    $board = Board::factory()->forUniversity($user->university)->create();
    $thread = Thread::factory()->create(['board_id' => $board->id, 'user_id' => $user->id]);

    $response = $this->actingAs($user)->post(route('posts.store', $thread), [
        'content' => 'テスト投稿の本文です。',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('posts', [
        'content' => 'テスト投稿の本文です。',
        'thread_id' => $thread->id,
        'user_id' => $user->id,
    ]);
});

test('他大学のスレッドには投稿できない', function () {
    $user = User::factory()->create();
    $otherUniversity = University::factory()->create();
    $board = Board::factory()->forUniversity($otherUniversity)->create();
    $thread = Thread::factory()->create(['board_id' => $board->id]);

    $response = $this->actingAs($user)->post(route('posts.store', $thread), [
        'content' => '不正投稿',
    ]);

    $response->assertForbidden();
    $this->assertDatabaseMissing('posts', ['content' => '不正投稿']);
});

test('未ログインユーザーは投稿できない', function () {
    $board = Board::factory()->create(['university_id' => null]);
    $thread = Thread::factory()->create(['board_id' => $board->id]);

    $response = $this->post(route('posts.store', $thread), [
        'content' => '未ログイン投稿',
    ]);

    $response->assertRedirect(route('login'));
});

test('投稿内容は必須', function () {
    $user = User::factory()->create();
    $board = Board::factory()->forUniversity($user->university)->create();
    $thread = Thread::factory()->create(['board_id' => $board->id, 'user_id' => $user->id]);

    $response = $this->actingAs($user)->post(route('posts.store', $thread), [
        'content' => '',
    ]);

    $response->assertSessionHasErrors('content');
});

test('投稿内容は1000文字以内', function () {
    $user = User::factory()->create();
    $board = Board::factory()->forUniversity($user->university)->create();
    $thread = Thread::factory()->create(['board_id' => $board->id, 'user_id' => $user->id]);

    $response = $this->actingAs($user)->post(route('posts.store', $thread), [
        'content' => str_repeat('あ', 1001),
    ]);

    $response->assertSessionHasErrors('content');
});

// ============================================================
// 投稿削除
// ============================================================

test('投稿を作成したユーザーは削除できる', function () {
    $user = User::factory()->create();
    $board = Board::factory()->forUniversity($user->university)->create();
    $thread = Thread::factory()->create(['board_id' => $board->id, 'user_id' => $user->id]);
    $post = Post::factory()->create(['thread_id' => $thread->id, 'user_id' => $user->id, 'post_number' => 1]);

    $response = $this->actingAs($user)->delete(route('posts.destroy', $post));

    $response->assertRedirect();
    $this->assertSoftDeleted('posts', ['id' => $post->id]);
});

test('他人の投稿は削除できない', function () {
    $user = User::factory()->create();
    $other = User::factory()->create(['university_id' => $user->university_id]);
    $board = Board::factory()->forUniversity($user->university)->create();
    $thread = Thread::factory()->create(['board_id' => $board->id, 'user_id' => $user->id]);
    $post = Post::factory()->create(['thread_id' => $thread->id, 'user_id' => $other->id, 'post_number' => 1]);

    $response = $this->actingAs($user)->delete(route('posts.destroy', $post));

    $response->assertForbidden();
    $this->assertNotSoftDeleted('posts', ['id' => $post->id]);
});

// ============================================================
// いいね (トグル)
// ============================================================

test('投稿にいいねできる', function () {
    $user = User::factory()->create();
    $board = Board::factory()->forUniversity($user->university)->create();
    $thread = Thread::factory()->create(['board_id' => $board->id, 'user_id' => $user->id]);
    $post = Post::factory()->create(['thread_id' => $thread->id, 'user_id' => $user->id, 'post_number' => 1]);

    $response = $this->actingAs($user)->postJson(route('posts.like', $post));

    $response->assertOk()->assertJsonFragment(['liked' => true]);
    $this->assertDatabaseHas('post_likes', ['user_id' => $user->id, 'post_id' => $post->id]);
});

test('いいね済みの投稿を再度押すといいねが外れる', function () {
    $user = User::factory()->create();
    $board = Board::factory()->forUniversity($user->university)->create();
    $thread = Thread::factory()->create(['board_id' => $board->id, 'user_id' => $user->id]);
    $post = Post::factory()->create(['thread_id' => $thread->id, 'user_id' => $user->id, 'post_number' => 1]);
    $post->likes()->attach($user->id);

    $response = $this->actingAs($user)->postJson(route('posts.like', $post));

    $response->assertOk()->assertJsonFragment(['liked' => false]);
    $this->assertDatabaseMissing('post_likes', ['user_id' => $user->id, 'post_id' => $post->id]);
});

test('他大学の投稿にはいいねできない', function () {
    $user = User::factory()->create();
    $otherUniversity = University::factory()->create();
    $board = Board::factory()->forUniversity($otherUniversity)->create();
    $thread = Thread::factory()->create(['board_id' => $board->id]);
    $post = Post::factory()->create(['thread_id' => $thread->id, 'post_number' => 1]);

    $response = $this->actingAs($user)->postJson(route('posts.like', $post));

    $response->assertForbidden();
    $this->assertDatabaseMissing('post_likes', ['user_id' => $user->id, 'post_id' => $post->id]);
});

test('共通ボードに投稿できる', function () {
    $user = User::factory()->create();
    $board = Board::factory()->create(['university_id' => null]);
    $thread = Thread::factory()->create(['board_id' => $board->id]);

    $response = $this->actingAs($user)->post(route('posts.store', $thread), [
        'content' => '共通ボードへの投稿です。',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('posts', [
        'content' => '共通ボードへの投稿です。',
        'thread_id' => $thread->id,
        'user_id' => $user->id,
    ]);
});

test('削除済み投稿への返信は拒否される', function () {
    $user = User::factory()->create();
    $board = Board::factory()->forUniversity($user->university)->create();
    $thread = Thread::factory()->create(['board_id' => $board->id]);
    $post = Post::factory()->create(['thread_id' => $thread->id, 'post_number' => 1]);
    $post->delete();

    $response = $this->actingAs($user)->post(route('posts.mention', [$thread, $post]), [
        'content' => '削除済みへの返信',
    ]);

    $response->assertNotFound();
    $this->assertDatabaseMissing('posts', ['content' => '削除済みへの返信']);
});
