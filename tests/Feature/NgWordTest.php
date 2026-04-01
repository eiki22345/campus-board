<?php

use App\Models\Board;
use App\Models\NgWord;
use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Support\Facades\Http;


function fakeYahooApiDown(): void
{
  Http::fake([
    'jlp.yahooapis.jp/*' => Http::response('error', 500),
  ]);
}

// ============================================================
// スレッド作成 (ThreadController@store)
// ============================================================

test('スレッドタイトルにNGワードが含まれる場合は拒否される', function () {
  fakeYahooApiDown();
  NgWord::create(['word' => 'テストNG']);

  $user = User::factory()->create();
  $board = Board::factory()->forUniversity($user->university)->create();

  $response = $this->actingAs($user)->post(route('threads.store', $board), [
    'title' => 'これはテストNGワードです',
    'content' => '普通の本文',
  ]);

  $response->assertSessionHasErrors('title');
  $this->assertDatabaseMissing('threads', ['title' => 'これはテストNGワードです']);
});

test('スレッド本文にNGワードが含まれる場合は拒否される', function () {
  fakeYahooApiDown();
  NgWord::create(['word' => 'テストNG']);

  $user = User::factory()->create();
  $board = Board::factory()->forUniversity($user->university)->create();

  $response = $this->actingAs($user)->post(route('threads.store', $board), [
    'title' => '普通のタイトル',
    'content' => 'テストNGワードが本文に含まれています',
  ]);

  $response->assertSessionHasErrors('content');
  $this->assertDatabaseMissing('threads', ['title' => '普通のタイトル']);
});

test('NGワードを含まないスレッドは作成できる', function () {
  fakeYahooApiDown();
  NgWord::create(['word' => 'テストNG']);

  $user = User::factory()->create();
  $board = Board::factory()->forUniversity($user->university)->create();

  $response = $this->actingAs($user)->post(route('threads.store', $board), [
    'title' => '普通のタイトル',
    'content' => '普通の本文です。',
  ]);

  $response->assertSessionDoesntHaveErrors(['title', 'content']);
});

// ============================================================
// 投稿作成 (PostController@store)
// ============================================================

test('投稿本文にNGワードが含まれる場合は拒否される', function () {
  fakeYahooApiDown();
  NgWord::create(['word' => 'テストNG']);

  $user = User::factory()->create();
  $board = Board::factory()->forUniversity($user->university)->create();
  $thread = Thread::factory()->create(['board_id' => $board->id]);

  $response = $this->actingAs($user)->post(route('posts.store', $thread), [
    'content' => 'テストNGワードが含まれています',
  ]);

  $response->assertSessionHasErrors('content');
  $this->assertDatabaseMissing('posts', ['content' => 'テストNGワードが含まれています']);
});

test('NGワードを含まない投稿は作成できる', function () {
  fakeYahooApiDown();
  NgWord::create(['word' => 'テストNG']);

  $user = User::factory()->create();
  $board = Board::factory()->forUniversity($user->university)->create();
  $thread = Thread::factory()->create(['board_id' => $board->id]);

  $response = $this->actingAs($user)->post(route('posts.store', $thread), [
    'content' => '問題のない投稿内容です。',
  ]);

  $response->assertSessionDoesntHaveErrors('content');
});
