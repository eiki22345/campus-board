<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{

    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        return [

            Stat::make('総ユーザー数', User::count())
                ->description('現在登録されている全ユーザー')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),


            Stat::make('総投稿数', Post::count())
                ->description('これまでの全レス数')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('primary'),


            Stat::make('未対応の通報', Report::where('status', 'pending')->count())
                ->description('至急確認してください')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger')
                ->chart([1, 5, 2, 8, 3, 10, 1]),
        ];
    }
}
