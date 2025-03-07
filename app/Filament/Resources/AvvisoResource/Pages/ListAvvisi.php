<?php

namespace App\Filament\Resources\AvvisoResource\Pages;

use App\Filament\Resources\AvvisoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAvvisi extends ListRecords
{
    protected static string $resource = AvvisoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
