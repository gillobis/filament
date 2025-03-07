<?php

namespace App\Filament\Widgets;

use App\Enums\AvvisoPrioritaEnum;
use App\Filament\Resources\LetturaResource;
use App\Models\Lettura;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Letture extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Letture';

    private $data;

    public function __construct()
    {
        $this->data = Lettura::max('data');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                LetturaResource::getEloquentQuery()
                    ->with('avvisi')
                    ->with('contatore')
                    ->with('utenza')
            )
            ->defaultSort('created_at', 'desc')
            ->heading('Letture del ' . Carbon::parse($this->data)->format('d/m/Y'))
            ->filters([
                Tables\Filters\Filter::make('data')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('data')
                            ->default($this->data)

                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['data'],
                                fn(Builder $query, $date): Builder => $query->whereDate('data', '=', $date),
                            );
                    }),
                Tables\Filters\SelectFilter::make('sanitaria')
                    ->options([
                        '' => 'Tutti',
                        '1' => 'SI',
                        '0' => 'NO',
                    ])
                    ->native(false)
                    ->query(fn($data, Builder $query): Builder => $query->whereHas('contatore', fn(Builder $query) => $data['value'] ? $query->where('sanitaria', $data['value']) : $query)),
                Tables\Filters\SelectFilter::make('avviso')
                    ->options([
                        '' => 'Tutti',
                        'WARNING' => 'Da controllare',
                        'ERROR' => 'Errore',
                    ])
                    ->native(false)
                    ->query(
                        fn($data, Builder $query): Builder => $data['value']
                            ? $query->whereHas('avvisi', fn(Builder $query) => $query->where('priorita', $data['value']))
                            : $query
                    ),
            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->columns([
                Tables\Columns\TextColumn::make('utenza.riferimento_planimetrico')
                    ->label('Rif planimetrico')
                    ->sortable(),
                Tables\Columns\TextColumn::make('utenza.nome')
                    ->label('Nome')
                    ->sortable(),
                Tables\Columns\TextColumn::make('utenza.indirizzo')
                    ->label('Indirizzo')
                    ->sortable(),
                Tables\Columns\IconColumn::make('contatore.sanitaria')
                    ->label('Sanitaria')
                    ->alignCenter()
                    ->boolean()
                    ->sortable(),
                Tables\Columns\IconColumn::make('avviso')
                    ->icon(fn(string $state): string => match ($state) {
                        'WARNING' => 'bi-circle-fill',
                        'ERRORE' => 'bi-circle-fill',
                        default => 'bi-circle-fill',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'WARNING' => 'warning',
                        'ERRORE' => 'danger',
                        default => 'success',
                    })
                    ->alignCenter()
                    ->getStateUsing(function (Model $lettura): string {
                        // return whatever you need to show
                        if (!$lettura->avvisi->count())
                            return 'OK';
                        else {
                            $avvisi = $lettura->avvisi;
                            foreach ($avvisi as $avviso) {
                                if ($avviso->priorita ==  AvvisoPrioritaEnum::ERRORE->value)
                                    return 'ERRORE';
                            }

                            return 'WARNING';
                        }
                    })
            ])
            ->recordUrl(
                fn(Model $lettura): string => route('filament.admin.resources.letture.edit', ['tenant' => \Filament\Facades\Filament::getTenant(), 'record' => $lettura]),
            );
    }
}
