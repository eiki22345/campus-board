<?php

use App\Models\Board;
use App\Models\Thread;
use App\Models\University;
use App\Models\User;

// ============================================================
// 未ログインユーザーのアクセス制御
// ============================================================

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

// ============================================================
// スレッドいいね
// ============================================================

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
