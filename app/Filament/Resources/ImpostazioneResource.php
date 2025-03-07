<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ImpostazioneResource\Pages;
use App\Filament\Resources\ImpostazioneResource\RelationManagers;
use App\Models\Impostazione;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ImpostazioneResource extends Resource
{
    protected static ?string $model = Impostazione::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?int $navigationSort = 5;
    protected static ?string $modelLabel = 'impostazioni';
    protected static ?string $pluralModelLabel = 'impostazioni';
    protected static ?string $breadcrumb = 'Impostazioni';
    protected static ?string $slug = 'impostazioni';

    protected static bool $isScopedToTenant = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make([
                    'sm' => 3,
                    'xl' => 4,
                ])->schema([
                    Forms\Components\TextInput::make('nome')->required(),
                    Forms\Components\TextInput::make('valore')->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')->sortable(),
                Tables\Columns\TextColumn::make('valore')->sortable(),
            ])
            ->paginated(false)
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton(),
            ])
            ->bulkActions([
                /*  Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]), */]);
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
            'index' => Pages\ListImpostazioni::route('/'),
            'create' => Pages\CreateImpostazione::route('/create'),
            'edit' => Pages\EditImpostazione::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
