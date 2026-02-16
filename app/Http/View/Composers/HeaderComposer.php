<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HeaderComposer
{
  /**
   * データをビューにバインドする
   */
  public function compose(View $view): void
  {
    if (Auth::check()) {
      $unread_count = Cache::store('array')->remember(
        'unread_count_user_' . Auth::id(),
        60,
        fn() => Auth::user()->unreadNotifications()->count()
      );
      $view->with('unread_count', $unread_count);
    } else {
      $view->with('unread_count', 0);
    }
  }
}
