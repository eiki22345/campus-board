<?php

use App\Models\UniversityRequest;

test('大学追加リクエストフォームが表示される', function () {
  $response = $this->get(route('university-request.create'));

  $response->assertOk();
  $response->assertSee('大学追加リクエスト');
});

test('大学追加リクエストを送信できる', function () {
  $response = $this->post(route('university-request.store'), [
    'name' => '北海学園大学',
    'email' => 'tarou@hgu.jp',
  ]);

  $response->assertRedirect(route('university-request.create'));
  $response->assertSessionHas('status');
  $this->assertDatabaseHas('university_requests', [
    'name' => '北海学園大学',
    'email' => 'tarou@hgu.jp',
  ]);
});

test('大学名は必須', function () {
  $response = $this->post(route('university-request.store'), [
    'name' => '',
    'email' => 'tarou@hgu.jp',
  ]);

  $response->assertSessionHasErrors('name');
});

test('メールアドレスは必須', function () {
  $response = $this->post(route('university-request.store'), [
    'name' => '北海学園大学',
    'email' => '',
  ]);

  $response->assertSessionHasErrors('email');
});

test('有効なメールアドレス形式が必要', function () {
  $response = $this->post(route('university-request.store'), [
    'name' => '北海学園大学',
    'email' => 'invalid-email',
  ]);

  $response->assertSessionHasErrors('email');
});

test('フリーメールアドレスは拒否される', function (string $email) {
  $response = $this->post(route('university-request.store'), [
    'name' => '北海学園大学',
    'email' => $email,
  ]);

  $response->assertSessionHasErrors('email');
  $this->assertDatabaseMissing('university_requests', [
    'name' => '北海学園大学',
  ]);
})->with([
  'gmail' => 'user@gmail.com',
  'yahoo.co.jp' => 'user@yahoo.co.jp',
  'yahoo.com' => 'user@yahoo.com',
  'hotmail' => 'user@hotmail.com',
  'outlook.com' => 'user@outlook.com',
  'outlook.jp' => 'user@outlook.jp',
  'icloud' => 'user@icloud.com',
  'me.com' => 'user@me.com',
  'softbank' => 'user@softbank.ne.jp',
  'docomo' => 'user@docomo.ne.jp',
  'ezweb' => 'user@ezweb.ne.jp',
  'au' => 'user@au.com',
]);

test('大学メールアドレスは受け付けられる', function () {
  $response = $this->post(route('university-request.store'), [
    'name' => '北海学園大学',
    'email' => 'tarou@hgu.jp',
  ]);

  $response->assertSessionDoesntHaveErrors('email');
});

test('大学名は255文字以内', function () {
  $response = $this->post(route('university-request.store'), [
    'name' => str_repeat('あ', 256),
    'email' => 'tarou@hgu.jp',
  ]);

  $response->assertSessionHasErrors('name');
});

test('メールアドレスは255文字以内', function () {
  $response = $this->post(route('university-request.store'), [
    'name' => '北海学園大学',
    'email' => str_repeat('a', 249) . '@hgu.jp',
  ]);

  $response->assertSessionHasErrors('email');
});
