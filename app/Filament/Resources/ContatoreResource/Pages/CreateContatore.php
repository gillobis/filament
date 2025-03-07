<?php

namespace App\Filament\Resources\ContatoreResource\Pages;

use App\Filament\Resources\ContatoreResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateContatore extends CreateRecord
{
    protected static string $resource = ContatoreResource::class;

    protected static ?string $title = 'Contatori';
}
