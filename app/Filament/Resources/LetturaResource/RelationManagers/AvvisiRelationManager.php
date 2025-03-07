<?php

namespace App\Filament\Resources\LetturaResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Checkbox;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AvvisiRelationManager extends RelationManager
{
    protected $listeners = ['refreshRelation' => '$refresh'];

    protected static string $relationship = 'avvisi';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('tipo')->readOnly()->required()->string(),
                Textarea::make('descrizione')->string(),
                Checkbox::make('gestito')
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Avvisi')
            ->columns([
                Tables\Columns\TextColumn::make('tipo'),
                Tables\Columns\TextColumn::make('descrizione')->wrap(),
                Tables\Columns\IconColumn::make('priorita')
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
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->avvisi->count() > 0;
    }
}
