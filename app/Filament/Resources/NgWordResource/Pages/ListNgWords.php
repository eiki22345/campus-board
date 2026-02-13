<?php

namespace App\Filament\Resources\NgWordResource\Pages;

use App\Filament\Resources\NgWordResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNgWords extends ListRecords
{
    protected static string $resource = NgWordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
