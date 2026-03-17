<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Thread;
use Illuminate\Console\Command;

class PurgeOldIps extends Command
{
    protected $signature = 'purge:old-ips';

    protected $description = '6ヶ月以上経過し、通報のないスレッド・投稿のIPアドレスをnullにする';

    public function handle(): void
    {
        $threshold = now()->subMonths(6);

        $threads = Thread::where('created_at', '<', $threshold)
            ->whereNotNull('ip_address')
            ->whereDoesntHave('reports')
            ->update(['ip_address' => null]);

        $posts = Post::where('created_at', '<', $threshold)
            ->whereNotNull('ip_address')
            ->whereDoesntHave('reports')
            ->update(['ip_address' => null]);

        $this->info("IPアドレスを削除しました: スレッド {$threads} 件、投稿 {$posts} 件");
    }
}
