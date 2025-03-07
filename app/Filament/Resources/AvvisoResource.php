<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Avviso;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\FormsComponent;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\ToggleButtons;
use App\Filament\Resources\AvvisoResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AlertResource\RelationManagers;
use App\Filament\Resources\LetturaResource\Pages\EditLettura;
use App\Models\Lettura;
use Illuminate\Database\Eloquent\Model;

class AvvisoResource extends Resource
{
    protected static ?string $model = Avviso::class;

    protected static ?int $navigationSort = 4;
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $modelLabel = 'avvisi';
    protected static ?string $pluralModelLabel = 'avvisi';
    protected static ?string $breadcrumb = 'Avvisi';
    protected static ?string $slug = 'avvisi';

    protected static ?string $navigationLabel = 'Avvisi';

    /* public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereDate('created_at', now())->count();
    } */

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('tipo')->required()->string(),
                TextInput::make('descrizione')->string(),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('utenza.riferimento_planimetrico')->label('Rif planimetrico'),
                TextColumn::make('utenza.nome')
                    ->label('Nome'),
                TextColumn::make('utenza.indirizzo')
                    ->label('Indirizzo'),
                TextColumn::make('tipo'),
                TextColumn::make('descrizione')->limit(30),
                TextColumn::make('lettura.data')->label('Data lettura')->date('d/m/Y')->sortable(),
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
            ->filters([
                Tables\Filters\Filter::make('data')
                    ->label('Data lettura')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('data')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['data'],
                                fn(Builder $query, $date): Builder => $query->whereHas('lettura', function (Builder $query) use ($date) {
                                    $query->whereDate('data', $date);
                                }),
                            );
                    }),
                Tables\Filters\SelectFilter::make('utenza')
                    ->label('Utenza')
                    ->relationship('utenza', 'nome')
                    ->searchable()
                    ->preload(true)
                    ->native(false),
            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make()
                    ->color('default')
                    ->iconButton()
                    ->url(function ($record) {
                        return LetturaResource::getUrl('edit', ['record' => $record->lettura->id]);
                    })
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                /* Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]), */])
            ->defaultSort('lettura.data', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAvvisi::route('/'),
            //'create' => Pages\CreateAlert::route('/create'),
            //'edit' => Pages\EditAvvisi::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
