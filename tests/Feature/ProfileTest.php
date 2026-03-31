<?php

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
