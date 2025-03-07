<?php

namespace App\Filament\Resources\ContatoreResource\Pages;

use App\Filament\Resources\ContatoreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContatore extends EditRecord
{
    protected static string $resource = ContatoreResource::class;

    protected static ?string $title = 'Modifica contatore';

    public function getHeading(): string
    {
        return "Modifica contatore ".$this->getRecord()->getAttribute('id');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
