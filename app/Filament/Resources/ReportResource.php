<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\Post;
use App\Models\Report;
use App\Models\Thread;
use App\Notifications\ReportResolved;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Notifications\Notification;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationLabel = '通報・治安維持';

    public static function getStatuses(): array
    {
        return [
            'pending' => '未対応',
            'processing' => '保留・調査中',
            'resolved' => '対処済み',
            'rejected' => '却下',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),

                TextColumn::make('status')
                    ->label('状態')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => self::getStatuses()[$state] ?? $state)
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'danger',
                        'processing' => 'warning',
                        'resolved' => 'success',
                        'rejected' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('content')
                    ->label('投稿内容')
                    ->getStateUsing(function (Report $record): string {
                        $post = Post::withTrashed()->find($record->post_id);
                        $thread = Thread::withTrashed()->find($record->thread_id);
                        $text = $post?->content ?? $thread?->title ?? '（不明）';
                        $deleted = ($post?->trashed() || $thread?->trashed()) ? ' 🗑️' : '';
                        return $text . $deleted;
                    })
                    ->limit(40),

                TextColumn::make('reason')
                    ->label('理由')
                    ->limit(40)
                    ->tooltip(fn(Report $record): string => $record->reason ?? ''),

                TextColumn::make('created_at')
                    ->label('通報日時')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('状態フィルタ')
                    ->options(self::getStatuses()),
            ])
            ->actions([
                Action::make('updateStatus')
                    ->label('ステータス変更')
                    ->icon('heroicon-o-pencil-square')
                    ->form([
                        Select::make('status')
                            ->label('新しいステータス')
                            ->options(self::getStatuses())
                            ->required()
                            ->default(fn(Report $record) => $record->status),
                    ])
                    ->action(function (Report $record, array $data) {
                        $record->status = $data['status'];
                        $record->save();

                        Notification::make()
                            ->title('ステータス更新完了')
                            ->success()
                            ->send();
                    }),

                Action::make('deleteTarget')
                    ->label('対象を削除して一括完了')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->modalHeading('削除と完了通知の送信')
                    ->modalDescription('この投稿またはスレッドを削除し、関連する全ての通報を「対処済み」にします。また、通報者全員にお礼の通知が送信されます。')
                    ->action(function (Report $record) {
                        if ($record->post) {
                            $related_reports = Report::where('post_id', $record->post_id)->with('user')->get();
                            foreach ($related_reports as $report_item) {
                                if ($report_item->user) {
                                    $report_item->user->notify(new ReportResolved());
                                }
                                $report_item->status = 'resolved';
                                $report_item->save();
                            }
                            $record->post->delete();
                        } elseif ($record->thread) {
                            $related_reports = Report::where('thread_id', $record->thread_id)->with('user')->get();
                            foreach ($related_reports as $report_item) {
                                if ($report_item->user) {
                                    $report_item->user->notify(new ReportResolved());
                                }
                                $report_item->status = 'resolved';
                                $report_item->save();
                            }
                            $record->thread->delete();
                        }

                        Notification::make()
                            ->title('処理完了')
                            ->body('対象を削除し、通報者へ通知を送信しました。')
                            ->success()
                            ->send();
                    })
                    ->visible(fn(Report $record) => ($record->post !== null || $record->thread !== null) && $record->status !== 'resolved'),

                DeleteAction::make()->label('通報を削除'),
            ])
            ->defaultSort('status', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
        ];
    }
}
