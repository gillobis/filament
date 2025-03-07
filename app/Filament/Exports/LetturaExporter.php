<?php

namespace App\Filament\Exports;

use Carbon\Carbon;
use App\Models\Lettura;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class LetturaExporter extends Exporter
{
    protected static ?string $model = Lettura::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('utenza.nome')->label('Nome utenza'), 
            ExportColumn::make('utenza.indirizzo')->label('Indirizzo utenza'), 
            ExportColumn::make('contatore.codice')->label('Contatore'),
            ExportColumn::make('tipo'), 
            ExportColumn::make('data')
                ->formatStateUsing(fn ($state) => Carbon::hasFormat($state, 'Y-m-d H:i:s') ? Carbon::parse($state)->format('d/m/Y') : 'Invalid Date' ),
            ExportColumn::make('ora'),
            ExportColumn::make('riferimento'),
            ExportColumn::make('energia_allineata'),
            ExportColumn::make('energia_consumo'),
            ExportColumn::make('energia_potenza'),
            ExportColumn::make('volume_allineato'),
            ExportColumn::make('volume_consumo'),
            ExportColumn::make('portata'),
            ExportColumn::make('t_mandata'),
            ExportColumn::make('t_ritorno'),
            ExportColumn::make('t_diff'),
            ExportColumn::make('lettura_ausiliaria_1'),
            ExportColumn::make('lettura_ausiliaria_2'),
            ExportColumn::make('lettura_ausiliaria_consumo_1'),
            ExportColumn::make('lettura_ausiliaria_consumo_2'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'L\'export delle letture export è stato completato con ' . number_format($export->successful_rows) . ' righe exportate.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' righe hanno fallito l\'export.';
        }

        return $body;
    }


}
