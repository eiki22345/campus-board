<?php

namespace App\Filament\Resources\NgWordResource\Pages;

use App\Filament\Resources\NgWordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNgWord extends EditRecord
{
    protected static string $resource = NgWordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
