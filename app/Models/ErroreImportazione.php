<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ErroreImportazione extends Model
{
    protected $table = 'errori_importazione';

    public function importazione(): BelongsTo
    {
        return $this->belongsTo(Importazione::class, 'importazione_id');
    }
}
