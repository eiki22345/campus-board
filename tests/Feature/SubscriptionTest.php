<?php

use App\Models\Board;
use App\Models\Thread;
use App\Models\University;
use App\Models\User;

test('スレッドを購読できる', function () {
  $user = User::factory()->create();
  $board = Board::factory()->forUniversity($user->university)->create();
  $thread = Thread::factory()->create(['board_id' => $board->id]);

  $response = $this->actingAs($user)->post(route('threads.subscribe', $thread));

  $response->assertRedirect();
  $this->assertDatabaseHas('thread_subscriptions', [
    'user_id' => $user->id,
    'thread_id' => $thread->id,
  ]);
});

test('購読済みのスレッドを再度押すと購読解除される', function () {
  $user = User::factory()->create();
  $board = Board::factory()->forUniversity($user->university)->create();
  $thread = Thread::factory()->create(['board_id' => $board->id]);
  $user->subscribedThreads()->attach($thread->id);

  $response = $this->actingAs($user)->post(route('threads.subscribe', $thread));

  $response->assertRedirect();
  $this->assertDatabaseMissing('thread_subscriptions', [
    'user_id' => $user->id,
    'thread_id' => $thread->id,
  ]);
});

test('他大学のスレッドは購読できない', function () {
  $user = User::factory()->create();
  $otherUniversity = University::factory()->create();
  $board = Board::factory()->forUniversity($otherUniversity)->create();
  $thread = Thread::factory()->create(['board_id' => $board->id]);

  $response = $this->actingAs($user)->post(route('threads.subscribe', $thread));

  $response->assertForbidden();
  $this->assertDatabaseMissing('thread_subscriptions', [
    'user_id' => $user->id,
    'thread_id' => $thread->id,
  ]);
});
