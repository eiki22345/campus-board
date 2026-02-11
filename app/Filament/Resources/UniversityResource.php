<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UniversityResource\Pages;
use App\Models\University;
use Filament\Forms\Form; // ★ v3 ではこれが必須
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables\Table; // ★ v3 ではこれが必須
use Filament\Tables\Columns\TextColumn;

class UniversityResource extends Resource
{
    protected static ?string $model = University::class;

    // ★ v3 のルール: 型は ?string でなければなりません
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = '大学管理';

    // ★ 引数と戻り値の型を「Form」に統一します
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('大学名')
                    ->required(),
                TextInput::make('email_domain')
                    ->label('ドメイン')
                    ->prefix('@')
                    ->required(),
                Select::make('region_id')
                    ->relationship('region', 'name')
                    ->label('地域')
                    ->required(),
            ]);
    }

    // ★ 引数と戻り値の型を「Table」に統一します
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->label('大学名')->searchable()->sortable(),
                TextColumn::make('email_domain')->label('ドメイン'),
                TextColumn::make('region.name')->label('地域')->sortable(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUniversities::route('/'),
            'create' => Pages\CreateUniversity::route('/create'),
            'edit' => Pages\EditUniversity::route('/{record}/edit'),
        ];
    }
}
