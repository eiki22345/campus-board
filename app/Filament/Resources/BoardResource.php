<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BoardResource\Pages;
use App\Models\Board;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BoardResource extends Resource
{
    protected static ?string $model = Board::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = '掲示板管理';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('掲示板名')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),


                Forms\Components\Select::make('major_category_id')
                    ->label('親カテゴリ')
                    ->relationship('majorCategory', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\Select::make('university_id')
                    ->label('所属大学')
                    ->relationship('university', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('共通（全大学）')
                    ->helperText('特定の大学専用の掲示板にする場合は選択してください。共通掲示板にする場合は空欄のままにしてください。'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),


                Tables\Columns\TextColumn::make('name')
                    ->label('掲示板名')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('majorCategory.name')
                    ->label('カテゴリ')
                    ->sortable()
                    ->badge(),


                Tables\Columns\TextColumn::make('university.name')
                    ->label('所属大学')
                    ->sortable()
                    ->placeholder('共通'),


                Tables\Columns\TextColumn::make('created_at')
                    ->label('作成日')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

                Tables\Filters\SelectFilter::make('major_category_id')
                    ->label('カテゴリ')
                    ->relationship('majorCategory', 'name'),


                Tables\Filters\SelectFilter::make('university_id')
                    ->label('大学')
                    ->relationship('university', 'name'),


                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBoards::route('/'),
            'create' => Pages\CreateBoard::route('/create'),
            'edit' => Pages\EditBoard::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
