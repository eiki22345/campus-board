<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NgWordResource\Pages;
use App\Models\NgWord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NgWordResource extends Resource
{
    protected static ?string $model = NgWord::class;

    protected static ?string $navigationIcon = 'heroicon-o-no-symbol';
    protected static ?string $navigationLabel = 'NGワード管理';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Textarea::make('word')
                    ->label('NGワード')
                    ->helperText('複数登録する場合は、改行で区切ってください。')
                    ->required()
                    ->rows(10)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('word')
                    ->label('単語')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('登録日')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNgWords::route('/'),
            'create' => Pages\CreateNgWord::route('/create'),
        ];
    }
}
