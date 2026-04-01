<?php

use App\Models\Board;
use App\Models\Post;
use App\Models\Report;
use App\Models\Thread;
use App\Models\University;
use App\Models\User;

// ============================================================
// スレッド通報
// ============================================================

test('スレッドを通報できる', function () {
  $user = User::factory()->create();
  $board = Board::factory()->forUniversity($user->university)->create();
  $thread = Thread::factory()->create(['board_id' => $board->id]);

  $response = $this->actingAs($user)->post(route('reports.store'), [
    'thread_id' => $thread->id,
    'reason' => 'spam',
  ]);

  $response->assertRedirect();
  $this->assertDatabaseHas('reports', [
    'user_id' => $user->id,
    'thread_id' => $thread->id,
    'reason' => 'spam',
  ]);
});

test('他大学のスレッドは通報できない', function () {
  $user = User::factory()->create();
  $otherUniversity = University::factory()->create();
  $board = Board::factory()->forUniversity($otherUniversity)->create();
  $thread = Thread::factory()->create(['board_id' => $board->id]);

  $response = $this->actingAs($user)->post(route('reports.store'), [
    'thread_id' => $thread->id,
    'reason' => 'spam',
  ]);

  $response->assertForbidden();
  $this->assertDatabaseMissing('reports', [
    'user_id' => $user->id,
    'thread_id' => $thread->id,
  ]);
});

// ============================================================
// 投稿通報
// ============================================================

test('投稿を通報できる', function () {
  $user = User::factory()->create();
  $board = Board::factory()->forUniversity($user->university)->create();
  $thread = Thread::factory()->create(['board_id' => $board->id]);
  $post = Post::factory()->create(['thread_id' => $thread->id, 'post_number' => 1]);

  $response = $this->actingAs($user)->post(route('reports.store'), [
    'post_id' => $post->id,
    'reason' => 'harassment',
  ]);

  $response->assertRedirect();
  $this->assertDatabaseHas('reports', [
    'user_id' => $user->id,
    'post_id' => $post->id,
    'reason' => 'harassment',
  ]);
});

test('他大学の投稿は通報できない', function () {
  $user = User::factory()->create();
  $otherUniversity = University::factory()->create();
  $board = Board::factory()->forUniversity($otherUniversity)->create();
  $thread = Thread::factory()->create(['board_id' => $board->id]);
  $post = Post::factory()->create(['thread_id' => $thread->id, 'post_number' => 1]);

  $response = $this->actingAs($user)->post(route('reports.store'), [
    'post_id' => $post->id,
    'reason' => 'spam',
  ]);

  $response->assertForbidden();
  $this->assertDatabaseMissing('reports', [
    'user_id' => $user->id,
    'post_id' => $post->id,
  ]);
});



test('同じスレッドを2回通報できない', function () {
  $user = User::factory()->create();
  $board = Board::factory()->forUniversity($user->university)->create();
  $thread = Thread::factory()->create(['board_id' => $board->id]);

  $this->actingAs($user)->post(route('reports.store'), [
    'thread_id' => $thread->id,
    'reason' => 'spam',
  ]);

  $response = $this->actingAs($user)->post(route('reports.store'), [
    'thread_id' => $thread->id,
    'reason' => 'spam',
  ]);

  $response->assertRedirect();
  $response->assertSessionHas('error');
  $this->assertDatabaseCount('reports', 1);
});

test('同じ投稿を2回通報できない', function () {
  $user = User::factory()->create();
  $board = Board::factory()->forUniversity($user->university)->create();
  $thread = Thread::factory()->create(['board_id' => $board->id]);
  $post = Post::factory()->create(['thread_id' => $thread->id, 'post_number' => 1]);

  $this->actingAs($user)->post(route('reports.store'), [
    'post_id' => $post->id,
    'reason' => 'spam',
  ]);

  $response = $this->actingAs($user)->post(route('reports.store'), [
    'post_id' => $post->id,
    'reason' => 'spam',
  ]);

  $response->assertRedirect();
  $response->assertSessionHas('error');
  $this->assertDatabaseCount('reports', 1);
});

// ============================================================
// バリデーション
// ============================================================

test('通報理由は必須', function () {
  $user = User::factory()->create();
  $board = Board::factory()->forUniversity($user->university)->create();
  $thread = Thread::factory()->create(['board_id' => $board->id]);

  $response = $this->actingAs($user)->post(route('reports.store'), [
    'thread_id' => $thread->id,
    'reason' => '',
  ]);

  $response->assertSessionHasErrors('reason');
  $this->assertDatabaseMissing('reports', ['thread_id' => $thread->id]);
});

test('通報理由は定義済みの値のみ許可される', function () {
  $user = User::factory()->create();
  $board = Board::factory()->forUniversity($user->university)->create();
  $thread = Thread::factory()->create(['board_id' => $board->id]);

  $response = $this->actingAs($user)->post(route('reports.store'), [
    'thread_id' => $thread->id,
    'reason' => 'invalid_reason',
  ]);

  $response->assertSessionHasErrors('reason');
});

test('その他を選んだ場合は詳細理由が必須', function () {
  $user = User::factory()->create();
  $board = Board::factory()->forUniversity($user->university)->create();
  $thread = Thread::factory()->create(['board_id' => $board->id]);

  $response = $this->actingAs($user)->post(route('reports.store'), [
    'thread_id' => $thread->id,
    'reason' => 'other',
    'reason_detail' => '',
  ]);

  $response->assertSessionHasErrors('reason_detail');
});

// ============================================================
// 削除済みコンテンツ
// ============================================================

test('削除済みスレッドは通報できない', function () {
  $user = User::factory()->create();
  $board = Board::factory()->forUniversity($user->university)->create();
  $thread = Thread::factory()->create(['board_id' => $board->id]);
  $thread->delete();

  $response = $this->actingAs($user)->post(route('reports.store'), [
    'thread_id' => $thread->id,
    'reason' => 'spam',
  ]);

  $response->assertSessionHasErrors('thread_id');
});

test('削除済み投稿は通報できない', function () {
  $user = User::factory()->create();
  $board = Board::factory()->forUniversity($user->university)->create();
  $thread = Thread::factory()->create(['board_id' => $board->id]);
  $post = Post::factory()->create(['thread_id' => $thread->id, 'post_number' => 1]);
  $post->delete();

  $response = $this->actingAs($user)->post(route('reports.store'), [
    'post_id' => $post->id,
    'reason' => 'spam',
  ]);

  $response->assertSessionHasErrors('post_id');
});

// ============================================================
// 自動削除（10件通報）
// ============================================================

test('スレッドが10件通報されると自動削除される', function () {
  $board = Board::factory()->create(['university_id' => null]);
  $thread = Thread::factory()->create(['board_id' => $board->id]);

  // 9件まで通報（削除されない）
  for ($i = 0; $i < 9; $i++) {
    $reporter = User::factory()->create();
    $this->actingAs($reporter)->post(route('reports.store'), [
      'thread_id' => $thread->id,
      'reason' => 'spam',
    ]);
  }
  $this->assertNotSoftDeleted('threads', ['id' => $thread->id]);

  // 10件目で自動削除
  $lastReporter = User::factory()->create();
  $this->actingAs($lastReporter)->post(route('reports.store'), [
    'thread_id' => $thread->id,
    'reason' => 'spam',
  ]);

  $this->assertSoftDeleted('threads', ['id' => $thread->id]);
});

test('投稿が10件通報されると自動削除される', function () {
  $board = Board::factory()->create(['university_id' => null]);
  $thread = Thread::factory()->create(['board_id' => $board->id]);
  $post = Post::factory()->create(['thread_id' => $thread->id, 'post_number' => 1]);

  // 9件まで通報（削除されない）
  for ($i = 0; $i < 9; $i++) {
    $reporter = User::factory()->create();
    $this->actingAs($reporter)->post(route('reports.store'), [
      'post_id' => $post->id,
      'reason' => 'spam',
    ]);
  }
  $this->assertNotSoftDeleted('posts', ['id' => $post->id]);

  // 10件目で自動削除
  $lastReporter = User::factory()->create();
  $this->actingAs($lastReporter)->post(route('reports.store'), [
    'post_id' => $post->id,
    'reason' => 'spam',
  ]);

  $this->assertSoftDeleted('posts', ['id' => $post->id]);
});
