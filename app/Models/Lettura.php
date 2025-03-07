<?php

namespace App\Models;

use App\Enums\AvvisoPrioritaEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Filament\Facades\Filament;

class Lettura extends Model
{
    use HasFactory;
    use \Znck\Eloquent\Traits\BelongsToThrough;

    protected $table = "letture";

    protected function casts(): array
    {
        return [
            'data' => 'datetime:Y-m-d',
            'ora' => 'datetime:H:i'
        ];
    }

    protected static function booted(): void
    {
        static::addGlobalScope('impianto', function (Builder $query) {
            $tennant = Filament::getTenant();
            if ($tennant)
                $query->where('letture.impianto_id', $tennant->id);
        });
    }

    public function contatore(): BelongsTo
    {
        return $this->belongsTo(Contatore::class, 'contatore_id');
    }

    public function utenza(): \Znck\Eloquent\Relations\BelongsToThrough
    {
        return $this->belongsToThrough(Utenza::class, Contatore::class, foreignKeyLookup: [Contatore::class => 'contatore_id', Utenza::class => 'utenza_id']);
    }

    public function impianto(): BelongsTo
    {
        return $this->belongsTo(Impianto::class, 'impianto_id');
    }

    public function avvisi(): HasMany
    {
        return $this->hasMany(Avviso::class, 'lettura_id');
    }

    public function check()
    {
        # elimino vecchi avvisi
        $this->avvisi()->delete();

        # Verifica se controllare contatori con sanitaria
        if ($this->contatore->sanitaria) {
            $checkSanitaria = Impostazione::find('check_sanitaria')->valore;
            if (!$checkSanitaria)
                return;
        }

        # CHECK 1: Lettura mancante
        if (is_null($this->energia_consumo)) {
            $this->avvisi()->create([
                'impianto_id' => $this->impianto_id,
                'tipo'        => 'Lettura mancante',
                'priorita'    => AvvisoPrioritaEnum::ERRORE,
                'descrizione' => 'Lettura non presente o non leggibile.'
            ]);
        }

        # CHECK 2: Errore sonda
        if ($this->energia_consumo === 0) {
            $this->avvisi()->create([
                'impianto_id' => $this->impianto_id,
                'tipo'        => 'Errore sonda',
                'priorita'    => AvvisoPrioritaEnum::ERRORE,
                'descrizione' => 'Ricevuto valore anomalo. Possibile errore sonda.'
            ]);
        }

        # CHECK 3: Valore fuori limite
        $tMin = Impostazione::find('t_min')->valore;
        $tMax = Impostazione::find('t_max')->valore;
        if ($this->t_mandata < $tMin || $this->t_mandata > $tMax) {
            $this->avvisi()->create([
                'impianto_id' => $this->impianto_id,
                'tipo'        => 'Valore  T Mandata fuori limite',
                'priorita'    => AvvisoPrioritaEnum::ERRORE,
                'descrizione' => 'Valore T Mandata fuori limite (' . $this->t_mandata . '°C)'
            ]);
        }
        if ($this->t_ritorno < $tMin || $this->t_ritorno > $tMax) {
            $this->avvisi()->create([
                'impianto_id' => $this->impianto_id,
                'tipo'        => 'Valore T Ritorno fuori limite',
                'priorita'    => AvvisoPrioritaEnum::ERRORE,
                'descrizione' => 'Valore  T Ritorno fuori limite (' . $this->t_ritorno . '°C)'
            ]);
        }

        # CHECK 4: Valore anomalo rispetto alla media del periodo (10gg)
        $contatore = $this->contatore;
        if ($contatore->letture()->count() > 10) {
            $media = $contatore->letture()->whereDate('data', '>=', now()->subDays(10))->whereDate('data', '<>', $this->data)->avg('energia_consumo');
            $media = round($media, 2);
            $percentualeScostamentoMedia = Impostazione::find('percentuale_scostamento_media')->valore;
            $percentualeScostamento = round((abs($this->energia_consumo - $media) / $media) * 100, 2);
            $simbolo = $this->energia_consumo > $media ? '+' : '-';
            if ($percentualeScostamento > $percentualeScostamentoMedia) {
                $this->avvisi()->create([
                    'impianto_id' => $this->impianto_id,
                    'tipo'        => 'Valore anomalo',
                    'priorita'    => AvvisoPrioritaEnum::WARNING,
                    'descrizione' => 'Valore anomalo rispetto alla media degli ultimi 10 giorni: ' . $this->energia_consumo . 'MWh / ' . $media . 'MWh (' . $simbolo. $percentualeScostamento . '%)'
                ]);
            }
        }

        # CHECK 5: Lettura uguale a quella precedente
        $letturaPrec = $contatore->letture()->whereDate('data', '<>', $this->data)->orderBy('data', 'desc')->first()->energia_consumo;
        if ($this->energia_consumo === $letturaPrec) {
            $this->avvisi()->create([
                'impianto_id' => $this->impianto_id,
                'tipo'        => 'Lettura uguale a quella precedente',
                'priorita'    => AvvisoPrioritaEnum::WARNING,
                'descrizione' => 'Lettura consumo uguale a quella precedente'
            ]);
        }
    }
}
