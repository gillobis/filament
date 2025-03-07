<?php

namespace App\Filament\Resources\LetturaResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\LetturaResource;
use App\Models\LogAzione;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EditLettura extends EditRecord
{
    protected static string $resource = LetturaResource::class;

    protected static ?string $title = "Modifica lettura";

    public function getHeading(): string
    {
        return "Lettura " . Carbon::parse($this->getRecord()->getAttribute('data'))->format('d/m/Y');
    }

    public function getSubheading(): ?string
    {
        return "Utenza " . $this->getRecord()->getRelationValue('utenza')->nome .
            " - Contatore " . $this->getRecord()->getRelationValue('contatore')->codice;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $diff = array_compare($data, $record->toArray());
        if (count($diff) > 0) {
            LogAzione::create([
                'user_id' => Auth::id(),
                'descrizione' => 'Modifica lettura ' . $record->id . ' [' . json_encode($diff) . ']'
            ]);
        }

        $record->update($data);
        $record->check();
        $record->refresh();
        $this->dispatch('refreshRelation');

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
