<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class PurgeDeletedAccounts extends Command
{
  protected $signature = 'purge:deleted-accounts';

  protected $description = '削除申請から7日経過したアカウントを完全削除する';

  public function handle(): void
  {
    $threshold = now()->subDays(7);

    $count = User::whereNotNull('deletion_requested_at')
      ->where('deletion_requested_at', '<=', $threshold)
      ->count();

    User::whereNotNull('deletion_requested_at')
      ->where('deletion_requested_at', '<=', $threshold)
      ->each(function (User $user) {
        $user->delete();
      });

    $this->info("期限切れアカウントを削除しました: {$count} 件");
  }
}
