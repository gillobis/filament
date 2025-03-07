<?php

namespace App\Filament\Resources\ImpostazioneResource\Pages;

use App\Filament\Resources\ImpostazioneResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListImpostazioni extends ListRecords
{
    protected static string $resource = ImpostazioneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
