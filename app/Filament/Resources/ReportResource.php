<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\Report;
use App\Notifications\ReportResolved; // ★追加: 通知クラスを読み込み
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Illuminate\Support\Str; // ★追加: 文字列操作用

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
        return $form->schema([
            Section::make('管理アクション')
                ->schema([
                    Select::make('status')
                        ->label('現在のステータス')
                        ->options(self::getStatuses())
                        ->required()
                        ->native(false),
                ]),
        ]);
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

                SelectColumn::make('status')
                    ->label('クイック更新')
                    ->options(self::getStatuses()),

                TextColumn::make('post.content')
                    ->label('投稿内容')
                    ->limit(30)
                    ->searchable(),

                TextColumn::make('reason')
                    ->label('理由')
                    ->limit(20)
                    ->tooltip(fn(Report $record): string => $record->reason ?? ''), // 長い理由はツールチップで表示

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
                Action::make('deletePost')
                    ->label('投稿を削除して一括完了')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->modalHeading('投稿削除と完了通知の送信')
                    ->modalDescription('この投稿を削除し、関連する全ての通報を「対処済み」にします。また、通報者全員にお礼の通知が送信されます。')
                    ->action(function (Report $record) {
                        // 対象の投稿が存在する場合のみ実行
                        if ($record->post) {
                            $post_id = $record->post_id;

                            // 通知用に投稿内容の一部を取得（削除前に確保）
                            $post_content_snippet = Str::limit($record->post->content, 20);

                            // 1. 同じ投稿に対する通報を全て取得（スネークケース変数）
                            $related_reports = Report::where('post_id', $post_id)->with('user')->get();

                            // 2. 通報者全員に通知を送信し、ステータスを更新
                            foreach ($related_reports as $report_item) {
                                // ユーザーが存在すれば通知を送る
                                if ($report_item->user) {
                                    $report_item->user->notify(new ReportResolved($post_content_snippet));
                                }

                                // ステータスを「対処済み」に変更
                                $report_item->update(['status' => 'resolved']);
                            }

                            // 3. 元の投稿を削除
                            $record->post->delete();

                            Notification::make()
                                ->title('処理完了')
                                ->body('投稿を削除し、通報者へ通知を送信しました。')
                                ->success()
                                ->send();
                        }
                    })
                    ->visible(fn(Report $record) => $record->post !== null && $record->status !== 'resolved'),

                DeleteAction::make()->label('通報を削除'),
            ])
            ->defaultSort('status', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
        ];
    }
}
