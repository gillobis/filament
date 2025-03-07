<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UtenzaResource\Pages;
use App\Filament\Resources\UtenzaResource\RelationManagers;
use App\Models\Utenza;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UtenzaResource extends Resource
{
    protected static ?string $model = Utenza::class;
    protected static ?string $modelLabel = 'utenze';
    protected static ?string $pluralModelLabel = 'utenze';
    protected static ?string $breadcrumb = 'Utenze';
    protected static ?string $slug = 'utenze';

    protected static ?string $navigationLabel = 'Utenze';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make([
                    'sm' => 3,
                    'xl' => 4,
                ])->schema([
                    Forms\Components\TextInput::make('riferimento_planimetrico')->readOnly(),
                    Forms\Components\TextInput::make('indirizzo')->readOnly(),
                    Forms\Components\TextInput::make('nome')->readOnly(),
                    Forms\Components\DatePicker::make('data_attivazione')->readOnly(),
                    Forms\Components\Checkbox::make('sanitaria')->inline(false)->disabled(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('riferimento_planimetrico')->label('Rif. Planim.')->sortable(),
                Tables\Columns\TextColumn::make('indirizzo')->sortable(),
                Tables\Columns\TextColumn::make('nome')->sortable(),
                Tables\Columns\TextColumn::make('data_attivazione')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('contatori_count')->label('Contatori')->counts('contatori')->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                /* Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]), */]);
    }


    public static function getRelations(): array
    {
        return [
            RelationManagers\ContatoriRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUtenze::route('/'),
            //'create' => Pages\CreateUtenza::route('/create'),
            'edit' => Pages\EditUtenza::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
