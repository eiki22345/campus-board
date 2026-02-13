<?php

namespace App\Filament\Resources\NgWordResource\Pages;

use App\Filament\Resources\NgWordResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateNgWord extends CreateRecord
{
    protected static string $resource = NgWordResource::class;


    protected function handleRecordCreation(array $data): Model
    {

        $words = explode("\n", $data['word']);

        $lastRecord = null;

        foreach ($words as $word) {
            $word = trim($word);

            if (!empty($word)) {
                $lastRecord = static::getModel()::firstOrCreate(['word' => $word]);
            }
        }


        return $lastRecord;
    }


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
