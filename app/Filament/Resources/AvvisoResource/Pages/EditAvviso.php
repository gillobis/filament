<?php

namespace App\Filament\Resources\AvvisoResource\Pages;

use App\Filament\Resources\AvvisoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAvviso extends EditRecord
{
    protected static string $resource = AvvisoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
