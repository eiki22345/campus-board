<?php

namespace App\Policies;

use App\Models\Thread;
use App\Models\User;

class ThreadPolicy
{
  /**
   * 管理者は全アクションを許可
   */
  public function before(User $user, string $ability): bool|null
  {
    if ($user->role === User::ROLE_ADMIN) {
      return true;
    }

    return null;
  }

  /**
   * スレッドの閲覧権限
   */
  public function view(User $user, Thread $thread): bool
  {
    $board = $thread->board;

    if (is_null($board->university_id)) {
      return true;
    }

    return $user->university_id === $board->university_id;
  }

  /**
   * スレッドの作成権限
   */
  public function create(User $user): bool
  {
    return true;
  }

  /**
   * スレッドの削除権限
   */
  public function delete(User $user, Thread $thread): bool
  {
    return $user->id === $thread->user_id;
  }
}
