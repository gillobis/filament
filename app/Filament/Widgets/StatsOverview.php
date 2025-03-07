<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\AvvisoResource;
use App\Filament\Resources\AvvisoResource\Pages\ListAvvisi;
use App\Filament\Resources\ContatoreResource\Pages\ListContatori;
use App\Models\Lettura;
use App\Models\Importazione;
use App\Models\LogAzione;
use App\Models\Utenza;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $ultimoImport = Importazione::latest()->limit(1)->first();
        $dataLetture = Lettura::latest()->limit(1)->select('data')->first()?->data;
        $nAvvisi = Lettura::where('data', '=', $dataLetture)->has('avvisi')->count();
        $ultimoControllo = LogAzione::where('descrizione', 'Controllo')->latest()->first();

        return [
            Stat::make('Ultimo controllo ', $ultimoControllo->created_at->format('d/m/Y H:i'))
                ->description('Effettuato da ' . $ultimoControllo->user->name),
            Stat::make('Ultimo import ', $ultimoImport?->created_at->format('d/m/Y H:i') ?: '-')
                ->description('Importate ' . ($ultimoImport?->letture_importate ?: 0) . ' letture'),
            Stat::make('Avvisi', $nAvvisi)
                ->description('Avvisi nell\'ultimo import')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color('danger')
                ->url(ListAvvisi::getUrl(['tableFilters[data][data]' => $ultimoImport?->created_at->format('Y-m-d')])),
        ];
    }
}
