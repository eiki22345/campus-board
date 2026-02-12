<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class HeaderComposer
{
  /**
   * データをビューにバインドする
   */
  public function compose(View $view): void
  {
    // ログインしている場合のみ未読件数を取得
    if (Auth::check()) {
      $unread_count = Auth::user()->unreadNotifications()->count();
      $view->with('unread_count', $unread_count);
    } else {
      $view->with('unread_count', 0);
    }
  }
}
