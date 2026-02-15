<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UniversityRequestResource\Pages;
use App\Models\Region;
use App\Models\University;
use App\Models\UniversityRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class UniversityRequestResource extends Resource
{
    protected static ?string $model = UniversityRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    protected static ?string $navigationLabel = '大学追加リクエスト';
    protected static ?string $modelLabel = '追加リクエスト';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('申請内容')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('大学名')
                            ->required()
                            ->readOnly(),
                        Forms\Components\TextInput::make('email')
                            ->label('メールアドレス')
                            ->email()
                            ->required()
                            ->readOnly(),
                        Forms\Components\TextInput::make('verification_url')
                            ->label('確認用URL')
                            ->url()
                            ->readOnly()
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Toggle::make('is_approved')
                    ->label('承認済み')
                    ->disabled()
                    ->onColor('success')
                    ->offColor('danger'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('申請日時')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('大学名')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('メールアドレス')
                    ->searchable(),
                Tables\Columns\TextColumn::make('verification_url')
                    ->label('URL')
                    ->limit(30)
                    ->url(fn($state) => $state, true)
                    ->color('primary'),
                Tables\Columns\IconColumn::make('is_approved')
                    ->label('状態')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('warning'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([

                Tables\Actions\Action::make('approve')
                    ->label('承認して作成')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn(UniversityRequest $record) => !$record->is_approved)

                    ->form(function (UniversityRequest $record) {

                        $domain = Str::after($record->email, '@');

                        return [
                            Forms\Components\TextInput::make('university_name')
                                ->label('登録する大学名')
                                ->default($record->name)
                                ->required(),
                            Forms\Components\TextInput::make('email_domain')
                                ->label('ドメイン')
                                ->default($domain)
                                ->required()
                                ->helperText('メールアドレスから自動抽出しました。必要なら修正してください。'),
                            Forms\Components\Select::make('region_id')
                                ->label('地域')
                                ->options(Region::all()->pluck('name', 'id'))
                                ->required()
                                ->searchable(),
                        ];
                    })
                    ->action(function (UniversityRequest $record, array $data) {

                        $university = University::firstOrCreate(
                            ['email_domain' => $data['email_domain']],
                            [
                                'name' => $data['university_name'],
                                'region_id' => $data['region_id'],
                            ]
                        );


                        $record->update(['is_approved' => true]);

                        Notification::make()
                            ->title('大学を作成し、リクエストを承認しました')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUniversityRequests::route('/'),
            'create' => Pages\CreateUniversityRequest::route('/create'),

        ];
    }
}
