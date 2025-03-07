<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Filament\Facades\Filament;

class Avviso extends Model
{
    use HasFactory;
    use \Znck\Eloquent\Traits\BelongsToThrough;

    protected $table = 'avvisi';

    protected $casts = [
        'gestito' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('impianto', function (Builder $query) {
            $tennant = Filament::getTenant();
            if ($tennant)
                $query->where('impianto_id', $tennant->id);
        });
    }

    public function lettura(): BelongsTo
    {
        return $this->belongsTo(Lettura::class, 'lettura_id');
    }

    public function impianto(): BelongsTo
    {
        return $this->belongsTo(Impianto::class, 'impianto_id');
    }

    public function utenza(): \Znck\Eloquent\Relations\BelongsToThrough
    {
        return $this->belongsToThrough(Utenza::class, [Contatore::class, Lettura::class], foreignKeyLookup: [Lettura::class => 'lettura_id', Contatore::class => 'contatore_id', Utenza::class => 'utenza_id']);
    }

    public function contatore(): \Znck\Eloquent\Relations\BelongsToThrough
    {
        return $this->belongsToThrough(Utenza::class, Contatore::class, foreignKeyLookup: [Contatore::class => 'contatore_id', Utenza::class => 'utenza_id']);
    }
}
