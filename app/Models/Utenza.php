<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Filament\Facades\Filament;

class Utenza extends Model
{
    use HasFactory;

    protected $table = "utenze";

    protected $guarded = [];

    protected static function booted(): void
    {
        static::addGlobalScope('impianto', function (Builder $query) {
            $tennant = Filament::getTenant();
            if ($tennant)
                $query->where('utenze.impianto_id', $tennant->id);
        });

        static::addGlobalScope('contatori_counts', function (Builder $builder) {
            $builder->withCount('contatori');
        });
    }

    public function impianto(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Impianto::class, 'impianto_id');
    }

    public function contatori(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(Contatore::class, 'utenza_id');
    }

    public function letture(): \Illuminate\Database\Eloquent\Relations\hasManyThrough
    {
        return $this->hasManyThrough(Lettura::class, Contatore::class, 'utenza_id', 'contatore_id');
    }
}
