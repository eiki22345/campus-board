<?php

namespace App\Filament\Resources\UniversityRequestResource\Pages;

use App\Filament\Resources\UniversityRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUniversityRequest extends EditRecord
{
    protected static string $resource = UniversityRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
