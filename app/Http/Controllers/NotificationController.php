<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class NotificationController extends Controller
{
    public function markAsRead(string $id): RedirectResponse
    {

        $notification = Auth::user()->notifications()->findOrFail($id);

        $notification->markAsRead();

        return back()->with('status', '通知を既読にしました。');
    }
}
