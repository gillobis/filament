<?php

namespace App\Filament\Resources\LetturaResource\Pages;

use App\Filament\Resources\LetturaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLetture extends ListRecords
{
    protected static string $resource = LetturaResource::class;

    protected static ?string $title = 'Letture';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
