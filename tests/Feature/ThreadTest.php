<?php

use App\Models\Board;
use App\Models\Thread;
use App\Models\University;
use App\Models\User;

test('スレッド一覧ページが表示される', function () {
    $user = User::factory()->create();
    $board = Board::factory()->forUniversity($user->university)->create();

    $response = $this->actingAs($user)->get(route('threads.index', $board));

    $response->assertOk();
});

test('他大学のボードのスレッド一覧は表示できない', function () {
    $user = User::factory()->create();
    $otherUniversity = University::factory()->create();
    $board = Board::factory()->forUniversity($otherUniversity)->create();

    $response = $this->actingAs($user)->get(route('threads.index', $board));

    $response->assertForbidden();
});

test('共通ボードのスレッド一覧はどの大学のユーザーでも表示できる', function () {
    $user = User::factory()->create();
    $board = Board::factory()->create(['university_id' => null]);

    $response = $this->actingAs($user)->get(route('threads.index', $board));

    $response->assertOk();
});

test('スレッドを作成できる', function () {
    $user = User::factory()->create();
    $board = Board::factory()->forUniversity($user->university)->create();

    $response = $this->actingAs($user)->post(route('threads.store', $board), [
        'title' => 'テストスレッドタイトル',
        'content' => 'テストスレッドの本文です。',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('threads', [
        'title' => 'テストスレッドタイトル',
        'board_id' => $board->id,
        'user_id' => $user->id,
    ]);
});

test('他大学のボードにはスレッドを作成できない', function () {
    $user = User::factory()->create();
    $otherUniversity = University::factory()->create();
    $board = Board::factory()->forUniversity($otherUniversity)->create();

    $response = $this->actingAs($user)->post(route('threads.store', $board), [
        'title' => '不正スレッド',
        'content' => '他大学のボードへの不正投稿',
    ]);

    $response->assertForbidden();
    $this->assertDatabaseMissing('threads', ['title' => '不正スレッド']);
});

test('未ログインユーザーはスレッドを作成できない', function () {
    $board = Board::factory()->create(['university_id' => null]);

    $response = $this->post(route('threads.store', $board), [
        'title' => 'テスト',
        'content' => 'テスト本文',
    ]);

    $response->assertRedirect(route('login'));
});

test('スレッドタイトルは必須', function () {
    $user = User::factory()->create();
    $board = Board::factory()->forUniversity($user->university)->create();

    $response = $this->actingAs($user)->post(route('threads.store', $board), [
        'title' => '',
        'content' => '本文',
    ]);

    $response->assertSessionHasErrors('title');
});

test('スレッドタイトルは50文字以内', function () {
    $user = User::factory()->create();
    $board = Board::factory()->forUniversity($user->university)->create();

    $response = $this->actingAs($user)->post(route('threads.store', $board), [
        'title' => str_repeat('あ', 51),
        'content' => '本文',
    ]);

    $response->assertSessionHasErrors('title');
});

test('共通ボードにスレッドを作成できる', function () {
    $user = User::factory()->create();
    $board = Board::factory()->create(['university_id' => null]);

    $response = $this->actingAs($user)->post(route('threads.store', $board), [
        'title' => '共通ボードのスレッド',
        'content' => '共通ボードへの投稿です。',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('threads', [
        'title' => '共通ボードのスレッド',
        'board_id' => $board->id,
        'user_id' => $user->id,
    ]);
});

test('スレッドの本文は必須', function () {
    $user = User::factory()->create();
    $board = Board::factory()->forUniversity($user->university)->create();

    $response = $this->actingAs($user)->post(route('threads.store', $board), [
        'title' => 'タイトル',
        'content' => '',
    ]);

    $response->assertSessionHasErrors('content');
});

test('スレッドの本文は1000文字以内', function () {
    $user = User::factory()->create();
    $board = Board::factory()->forUniversity($user->university)->create();

    $response = $this->actingAs($user)->post(route('threads.store', $board), [
        'title' => 'タイトル',
        'content' => str_repeat('あ', 1001),
    ]);

    $response->assertSessionHasErrors('content');
});


test('スレッドを作成したユーザーは削除できる', function () {
    $user = User::factory()->create();
    $board = Board::factory()->forUniversity($user->university)->create();
    $thread = Thread::factory()->create(['board_id' => $board->id, 'user_id' => $user->id]);

    $response = $this->actingAs($user)->delete(route('threads.destroy', [$board, $thread]));

    $response->assertRedirect();
    $this->assertSoftDeleted('threads', ['id' => $thread->id]);
});

test('他人のスレッドは削除できない', function () {
    $user = User::factory()->create();
    $other = User::factory()->create(['university_id' => $user->university_id]);
    $board = Board::factory()->forUniversity($user->university)->create();
    $thread = Thread::factory()->create(['board_id' => $board->id, 'user_id' => $other->id]);

    $response = $this->actingAs($user)->delete(route('threads.destroy', [$board, $thread]));

    $response->assertForbidden();
    $this->assertNotSoftDeleted('threads', ['id' => $thread->id]);
});

test('自大学のスレッド詳細ページを閲覧できる', function () {
    $user = User::factory()->create();
    $board = Board::factory()->forUniversity($user->university)->create();
    $thread = Thread::factory()->create(['board_id' => $board->id, 'user_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('threads.show', [$board, $thread]));

    $response->assertOk();
});

test('他大学のスレッド詳細ページは403になる', function () {
    $user = User::factory()->create();
    $otherUniversity = University::factory()->create();
    $board = Board::factory()->forUniversity($otherUniversity)->create();
    $thread = Thread::factory()->create(['board_id' => $board->id]);

    $response = $this->actingAs($user)->get(route('threads.show', [$board, $thread]));

    $response->assertForbidden();
});

test('共通ボードのスレッド詳細は誰でも閲覧できる', function () {
    $user = User::factory()->create();
    $board = Board::factory()->create(['university_id' => null]);
    $thread = Thread::factory()->create(['board_id' => $board->id]);

    $response = $this->actingAs($user)->get(route('threads.show', [$board, $thread]));

    $response->assertOk();
});
