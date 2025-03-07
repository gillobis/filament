<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Lettura;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Exports\LetturaExporter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use App\Filament\Resources\LetturaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LetturaResource\RelationManagers;
use App\Filament\Resources\LetturaResource\RelationManagers\AvvisiRelationManager;
use App\Models\LogAzione;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LetturaResource extends Resource
{
    protected static ?string $model = Lettura::class;
    protected static ?string $modelLabel = 'letture';
    protected static ?string $pluralModelLabel = 'letture';
    protected static ?string $breadcrumb = 'Letture';
    protected static ?string $slug = 'letture';

    protected static ?string $navigationLabel = 'Letture';
    protected static ?string $navigationIcon = 'heroicon-o-bolt';
    protected static ?int $navigationSort = 3;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Grid::make([
                    'sm' => 3,
                    'xl' => 4,
                ])->schema([
                    /* Forms\Components\Select::make('id')->relationship(name: 'impianto', titleAttribute: 'id')->disabled()->label('Codice impianto'),
                    Forms\Components\Select::make('nome')->relationship(name: 'impianto', titleAttribute: 'nome')->disabled()->label('Nome impianto'),
                    Forms\Components\Select::make('id')->relationship(name: 'contatore', titleAttribute: 'id')->disabled()->label('Contatore'),    */
                    Forms\Components\DatePicker::make('data')->required(),
                    Forms\Components\TimePicker::make('ora')->seconds(false)->format('H:i'),
                    Forms\Components\Select::make('tipo')->options([1, 2, 3, 4, 5])->native(false),
                    Forms\Components\TextInput::make('riferimento'),
                    Forms\Components\TextInput::make('energia_allineata')->numeric()->suffix('MWh'),
                    Forms\Components\TextInput::make('energia_consumo')->numeric()->suffix('MWh'),
                    Forms\Components\TextInput::make('energia_potenza')->numeric()->label('Potenza')->suffix('kW'),
                    Forms\Components\TextInput::make('volume_allineato')->numeric()->suffix('m3'),
                    Forms\Components\TextInput::make('volume_consumo')->numeric()->suffix('m3'),
                    Forms\Components\TextInput::make('portata')->numeric()->suffix('m3/h'),
                    Forms\Components\TextInput::make('t_mandata')->numeric()->label('Temperatura mandata')->suffix('°C'),
                    Forms\Components\TextInput::make('t_ritorno')->numeric()->label('Temperatura ritorno')->suffix('°C'),
                    Forms\Components\TextInput::make('t_diff')->numeric()->label('Differenza temperatura')->suffix('°C'),
                    Forms\Components\TextInput::make('lettura_ausiliaria_1')->numeric()->suffix('MWh'),
                    Forms\Components\TextInput::make('lettura_ausiliaria_2')->numeric()->suffix('MWh'),
                    Forms\Components\TextInput::make('lettura_ausiliaria_consumo_1')->numeric()->suffix('MWh'),
                    Forms\Components\TextInput::make('lettura_ausiliaria_consumo_2')->numeric()->suffix('MWh'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('utenza.nome')->sortable(),
                Tables\Columns\TextColumn::make('contatore.codice')->sortable(),
                Tables\Columns\TextColumn::make('tipo')->sortable(),
                Tables\Columns\TextColumn::make('data')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('ora')->time('H:i'),
                Tables\Columns\TextColumn::make('riferimento')->sortable(),
                Tables\Columns\TextColumn::make('energia_allineata')->numeric(1),
                Tables\Columns\TextColumn::make('energia_consumo')->numeric(1),
                Tables\Columns\TextColumn::make('energia_potenza')->numeric(1),
                Tables\Columns\TextColumn::make('volume_allineato')->numeric(1),
                Tables\Columns\TextColumn::make('volume_consumo')->numeric(1),
                Tables\Columns\TextColumn::make('portata')->numeric(1),
                Tables\Columns\TextColumn::make('t_mandata')->numeric(1),
                Tables\Columns\TextColumn::make('t_ritorno')->numeric(1),
                Tables\Columns\TextColumn::make('t_diff')->numeric(1),
                Tables\Columns\TextColumn::make('lettura_ausiliaria_1')->numeric(1),
                Tables\Columns\TextColumn::make('lettura_ausiliaria_2')->numeric(1),
                Tables\Columns\TextColumn::make('lettura_ausiliaria_consumo_1')->numeric(1),
                Tables\Columns\TextColumn::make('lettura_ausiliaria_consumo_2')->numeric(1),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('utenza')
                    ->relationship('utenza', 'nome')
                    ->searchable()
                    ->preload(true)
                    ->native(false),
                Tables\Filters\SelectFilter::make('contatore')
                    ->relationship('contatore', 'codice')
                    ->searchable()
                    ->native(false),
                Tables\Filters\Filter::make('data')
                    ->form([
                        Forms\Components\DatePicker::make('data')

                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['data'],
                                fn(Builder $query, $date): Builder => $query->whereDate('data', '=', $date),
                            );
                    })
            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                //Tables\Actions\EditAction::make()->color('default')->iconButton(),
            ])
            ->bulkActions([
                /* Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]), */
                ExportBulkAction::make()
                    ->exporter(LetturaExporter::class)
                    ->columnMapping(false)
                    ->requiresConfirmation(false)
                    ->fileName(fn (Export $export): string => "letture-{$export->getKey()}")
                    ->formats([
                        ExportFormat::Xlsx,
                        ExportFormat::Csv,
                    ])
            ])
            ->defaultSort('data', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            AvvisiRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLetture::route('/'),
            'create' => Pages\CreateLettura::route('/create'),
            'edit' => Pages\EditLettura::route('/{record}/edit')
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
