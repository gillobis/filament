<?php

namespace App\Filament\Resources\ContatoreResource\Pages;

use App\Filament\Resources\ContatoreResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContatori extends ListRecords
{
    protected static string $resource = ContatoreResource::class;

    protected static ?string $title = 'Contatori';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
