<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Filament\Facades\Filament;

class Contatore extends Model
{
    protected $table = "contatori";

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected static function booted(): void
    {
        static::addGlobalScope('impianto', function (Builder $query) {
            $tennant = Filament::getTenant();
            if ($tennant)
                $query->where('contatori.impianto_id', $tennant->id);
        });
    }

    public function impianto(): BelongsTo
    {
        return $this->belongsTo(Impianto::class, 'impianto_id');
    }

    public function utenze(): BelongsTo
    {
        return $this->belongsTo(Utenza::class, 'utenza_id');
    }

    public function letture(): HasMany
    {
        return $this->hasMany(Lettura::class, 'contatore_id');
    }
}
