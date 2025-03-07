<?php

namespace App\Filament\Resources\ImpostazioneResource\Pages;

use App\Filament\Resources\ImpostazioneResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImpostazione extends EditRecord
{
    protected static string $resource = ImpostazioneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
