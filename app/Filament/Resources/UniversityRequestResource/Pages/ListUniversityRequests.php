<?php

namespace App\Filament\Resources\UniversityRequestResource\Pages;

use App\Filament\Resources\UniversityRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUniversityRequests extends ListRecords
{
    protected static string $resource = UniversityRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
