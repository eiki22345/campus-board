<?php

use App\Models\University;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    University::factory()->create(['email_domain' => 'example.com']);

    $response = $this->post('/register', [
        'nickname' => 'testuser',
        'email' => 'test@example.com',
        'password' => 'Password1!',
        'password_confirmation' => 'Password1!',
        'agree' => '1',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('verification.notice', absolute: false));
});

test('users can register with subdomain email when parent domain is registered', function () {
    University::factory()->create(['email_domain' => 'hokudai.ac.jp']);

    $response = $this->post('/register', [
        'nickname' => 'testuser',
        'email' => 'test@agriculture.hokudai.ac.jp',
        'password' => 'Password1!',
        'password_confirmation' => 'Password1!',
        'agree' => '1',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('verification.notice', absolute: false));
});

test('users cannot register with unsupported email domain', function () {
    $response = $this->post('/register', [
        'nickname' => 'testuser',
        'email' => 'test@obihiro.ac.jp',
        'password' => 'Password1!',
        'password_confirmation' => 'Password1!',
        'agree' => '1',
    ]);

    $this->assertGuest();
    $response->assertSessionHas('error_message');
});
