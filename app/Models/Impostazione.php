<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Impostazione extends Model
{
    protected $table = "impostazioni";

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $guarded = [];
}
