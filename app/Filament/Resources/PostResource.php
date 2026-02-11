<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms\Form;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\DeleteAction;


class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = '投稿管理';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('user.nickname')->label('投稿者')->disabled(),
                Textarea::make('content')->label('内容')->rows(5)->required()->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('user.nickname')->label('投稿者')->searchable(),
                TextColumn::make('content')->label('内容')->limit(50)->searchable(),
                TextColumn::make('created_at')->label('日時')->dateTime()->sortable(),
            ])
            ->actions([
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
