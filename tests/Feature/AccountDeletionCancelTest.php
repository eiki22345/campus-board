<?php

use App\Models\User;
use Illuminate\Support\Facades\URL;

test('署名付きURLでアカウント削除をキャンセルできる', function () {
    $user = User::factory()->create(['deletion_requested_at' => now()]);

    $url = URL::signedRoute('account-deletion.cancel', ['user' => $user]);

    $response = $this->get($url);

    $response->assertRedirect(route('login'));
    $this->assertNull($user->fresh()->deletion_requested_at);
});

test('署名が不正なURLは拒否される', function () {
    $user = User::factory()->create(['deletion_requested_at' => now()]);

    $url = route('account-deletion.cancel', ['user' => $user]) . '?signature=invalid';

    $response = $this->get($url);

    $response->assertForbidden();
    $this->assertNotNull($user->fresh()->deletion_requested_at);
});

test('削除リクエストしていないユーザーはキャンセルできない', function () {
    $user = User::factory()->create(['deletion_requested_at' => null]);

    $url = URL::signedRoute('account-deletion.cancel', ['user' => $user]);

    $response = $this->get($url);

    $response->assertRedirect('/');
    $response->assertSessionHas('status', 'deletion-already-cancelled');
});
