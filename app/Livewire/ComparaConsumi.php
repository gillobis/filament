<?php

namespace App\Livewire;

use App\Models\Utenza;
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Grid;

use Filament\Forms\Form;

use Livewire\Component;

class ComparaConsumi extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    public ?array $results = [];
    public ?array $anni = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(4)
                    ->schema([
                        \Filament\Forms\Components\Select::make('utenza')
                            ->options(function () {
                                return \App\Models\Utenza::all()->pluck('nome', 'id');
                            })
                            ->multiple()
                            ->searchable()
                            ->required(),
                        \Filament\Forms\Components\DatePicker::make('da_data')->required(),
                        \Filament\Forms\Components\DatePicker::make('a_data')->required(),
                        \Filament\Forms\Components\Select::make('anni')
                            ->required()
                            ->selectablePlaceholder(false)
                            ->placeholder('')
                            ->native(false)
                            ->options([
                                1 => 1, 
                                2 => 2, 
                                3 => 3, 
                                4 => 4, 
                                5 => 5
                            ])
                            ->default(1)
                    ])

            ])
            ->statePath('data');
    }

    public function create(): void
    {

        $this->results = [];
        $this->anni = range(now()->year, now()->subYears($this->data['anni'])->year);

        foreach($this->data['utenza'] as $utenzaId) {
            $valori = [];
            $utenza = Utenza::findOrFail($utenzaId);
            $consumoPrecedente = 0;

            foreach ($this->anni as $index => $anno) {

                $daData = Carbon::parse($this->data['da_data'])->setYear($anno);
                $aData = Carbon::parse($this->data['a_data'])->setYear($anno);
                $consumo = $utenza->letture()
                    ->whereDate('data', '>=',$daData)
                    ->whereDate('data', '<=', $aData)
                    ->sum('energia_consumo');
                $gradiGiorno = Utenza::where('riferimento_planimetrico', 99999)->first()->letture()
                    ->whereDate('data', '>=',$daData)
                    ->whereDate('data', '<=', $aData)
                    ->sum('energia_consumo');
                
                if ($index > 0) {
                    $consumoPrecedente = $valori[$anno + 1][1];
                }

                $valori[$anno] = [
                    $consumoPrecedente ? ( $consumo - $consumoPrecedente ) / $consumoPrecedente * 100 : 0,
                    $consumo,
                    $gradiGiorno
                ];
            }

            $this->results[$utenza->nome] = $valori;
        }
    }

    public function render()
    {
        return view('livewire.compara-consumi');
    }
}
