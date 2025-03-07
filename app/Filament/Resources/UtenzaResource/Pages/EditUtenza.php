<?php

namespace App\Filament\Resources\UtenzaResource\Pages;

use App\Filament\Resources\UtenzaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUtenza extends EditRecord
{
    protected static string $resource = UtenzaResource::class;

    protected static ?string $title = "Modifica Utenza";

    public function getHeading(): string
    {
        return "Modifica utenza " . $this->getRecord()->getAttribute('nome');
    }


    protected function getHeaderActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
        ];
    }
}
