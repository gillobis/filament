<?php

namespace App\Filament\Pages;

use App\Models\Lettura;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Filament\Forms\Get;

class ComparaConsumi extends Page
{

    protected static string $view = 'filament.pages.compara-consumi';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
}
