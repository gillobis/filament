<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContatoreResource\Pages;
use App\Filament\Resources\ContatoreResource\RelationManagers;
use App\Models\Contatore;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContatoreResource extends Resource
{
    protected static ?string $model = Contatore::class;
    protected static ?string $modelLabel = 'contatori';
    protected static ?string $pluralModelLabel = 'contatori';
    protected static ?string $breadcrumb = 'Contatori';
    protected static ?string $slug = 'contatori';

    protected static ?string $navigationLabel = 'Contatori';
    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make([
                    'sm' => 3,
                    'xl' => 4,
                ])->schema([
                    Forms\Components\Select::make('utenza_id')->relationship('utenza', 'nome')->required(),
                    Forms\Components\TextInput::make('codice')->label('Codice contatore')->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('utenza.riferimento_planimetrico')->label('Rif. plan.')->sortable(),
                Tables\Columns\TextColumn::make('utenza.nome')->label('Nome')->sortable(),
                Tables\Columns\TextColumn::make('utenza.indirizzo')->label('Indirizzo')->sortable(),
                Tables\Columns\TextColumn::make('codice')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->color('default'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultGroup('utenza.nome');
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
            'index' => Pages\ListContatori::route('/'),
            'create' => Pages\CreateContatore::route('/create'),
            'edit' => Pages\EditContatore::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return false;
    }
}
