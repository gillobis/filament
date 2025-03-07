<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Importazione extends Model
{
    protected $table = 'importazioni';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'iniziato_alle' => 'datetime:Y-m-d H:i:s',
            'finito_alle' => 'datetime:Y-m-d H:i:s',
        ];
    }


    public function errori(): HasMany
    {
        return $this->hasMany(ErroreImportazione::class, 'importazione_id');
    }
}
