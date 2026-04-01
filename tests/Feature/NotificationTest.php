<?php

use App\Models\User;
use App\Notifications\ReportResolved;

test('自分の通知を既読にできる', function () {
    $user = User::factory()->create();
    $user->notify(new ReportResolved());
    $notification = $user->notifications()->first();

    $response = $this->actingAs($user)->post(route('notifications.read', $notification->id));

    $response->assertRedirect();
    $this->assertNotNull($user->notifications()->find($notification->id)->read_at);
});

test('他人の通知IDを指定すると404になる', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $other->notify(new ReportResolved());
    $otherNotification = $other->notifications()->first();

    $response = $this->actingAs($user)->post(route('notifications.read', $otherNotification->id));

    $response->assertNotFound();
});
