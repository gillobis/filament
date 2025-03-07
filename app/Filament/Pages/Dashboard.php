<?php

namespace App\Filament\Pages;

use App\Models\LogAzione;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Artisan;
use Filament\Notifications\Notification;
use Filament\Pages\Dashboard as BasePage;

class Dashboard extends BasePage
{
  protected function getHeaderActions(): array
  {
    return [
      Action::make('Controllo')
        ->color('info')
        ->icon('heroicon-c-check')
        ->action(function (array $data) {
          LogAzione::create([
            'user_id' => auth()->id(),
            'descrizione' => 'Controllo',
          ]);          
        })
        ->after(function() {
          Notification::make()
            ->body("Controllo registrato con successo")
            ->duration(5000)
            ->success()
            ->send();
        }),
      Action::make('Import letture')
        ->requiresConfirmation()
        ->icon('heroicon-c-arrow-down-on-square')
        ->action(function (array $data) {
          Artisan::call('app:import-letture');

          LogAzione::create([
            'user_id' => auth()->id(),
            'descrizione' => 'Import manuale letture',
          ]);          
        })->after(function() {
          Notification::make()
          ->body("Import effettuato con successo")
          ->duration(5000)
          ->success()
          ->send();
        })
    ];
  }
}
