<?php

use App\Models\Impostazione;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('impostazioni', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('nome');
            $table->string('valore');
        });

        Impostazione::create([
            'id' => 't_min',
            'nome' => 'Temperatura minima lettura',
            'valore' => 10
        ]);
        Impostazione::create([
            'id' => 't_max',
            'nome' => 'Temperatura massima lettura',
            'valore' => 150
        ]);
        Impostazione::create([
            'id' => 'check_sanitaria',
            'nome' => 'Verificare contatori con sanitaria',
            'valore' => 1
        ]);
        Impostazione::create([
            'id' => 'percentuale_scostamento_media',
            'nome' => 'Percentuale scostamento lettura da media del periodo',
            'valore' => 10
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('impostazioni');
    }
};
