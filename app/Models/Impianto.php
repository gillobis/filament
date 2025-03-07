<?php

namespace App\Models;

use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Impianto extends Model implements HasName
{
    protected $table = "impianti";

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    public function utenze(): HasMany
    {
        return $this->hasMany(Utenza::class, 'impianto_id');
    }

    public function contatori(): HasMany
    {
        return $this->hasMany(Contatore::class, 'impianto_id');
    }

    public function letture(): HasMany
    {
        return $this->hasMany(Lettura::class, 'impianto_id');
    }

    public function getFilamentName(): string
    {
        return $this->nome;
    }
}
