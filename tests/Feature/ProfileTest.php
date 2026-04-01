<?php

use App\Models\NgWord;
use App\Models\User;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/users/edit');

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/users/edit', [
            'nickname' => 'newname8',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('users.edit'));

    $user->refresh();

    $this->assertSame('newname8', $user->nickname);
});

test('user can request account deletion', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete('/users/edit', [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNotNull($user->fresh()->deletion_requested_at);
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/users/edit')
        ->delete('/users/edit', [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrorsIn('userDeletion', 'password')
        ->assertRedirect('/users/edit');

    $this->assertNull($user->fresh()->deletion_requested_at);
});

test('ニックネームは必須', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->patch(route('users.update'), [
        'nickname' => '',
    ]);

    $response->assertSessionHasErrors('nickname');
});

test('ニックネームは8文字以内', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->patch(route('users.update'), [
        'nickname' => str_repeat('a', 9),
    ]);

    $response->assertSessionHasErrors('nickname');
});

test('NGワードはニックネームに使用できない', function () {
    $user = User::factory()->create();
    NgWord::create(['word' => 'badword']);

    $response = $this->actingAs($user)->patch(route('users.update'), [
        'nickname' => 'badword',
    ]);

    $response->assertSessionHasErrors('nickname');
    $this->assertNotSame('badword', $user->fresh()->nickname);
});
