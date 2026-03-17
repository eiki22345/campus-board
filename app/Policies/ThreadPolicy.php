<?php

namespace App\Policies;

use App\Models\Board;
use App\Models\Thread;
use App\Models\User;

class ThreadPolicy
{

  public function before(User $user, string $ability): bool|null
  {
    if ($user->role === User::ROLE_ADMIN) {
      return true;
    }

    return null;
  }


  public function view(User $user, Thread $thread): bool
  {
    $board = $thread->board;

    if (is_null($board->university_id)) {
      return true;
    }

    return $user->university_id === $board->university_id;
  }


  public function create(User $user, Board $board): bool
  {
    if (is_null($board->university_id)) {
      return true;
    }

    return $user->university_id === $board->university_id;
  }


  public function delete(User $user, Thread $thread): bool
  {
    return $user->id === $thread->user_id;
  }
}
