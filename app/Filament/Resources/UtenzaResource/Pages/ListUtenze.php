<?php

namespace App\Filament\Resources\UtenzaResource\Pages;

use App\Filament\Resources\UtenzaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUtenze extends ListRecords
{
    protected static string $resource = UtenzaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
