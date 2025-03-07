<?php

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
        Schema::create('letture', function (Blueprint $table) {
            $table->id();
            $table->string('impianto_id');
            $table->foreign('impianto_id')->references('id')->on('impianti');
            $table->foreignId('contatore_id')->constrained('contatori');
            $table->string('contatore_codice', 10);
            $table->integer('tipo')->nullable();
            $table->date('data');
            $table->time('ora')->nullable();
            $table->string('riferimento')->nullable();
            $table->float('energia_allineata')->nullable();
            $table->float('energia_consumo')->nullable();
            $table->float('energia_potenza')->nullable();
            $table->float('volume_allineato')->nullable();
            $table->float('volume_consumo')->nullable();
            $table->float('portata')->nullable();
            $table->float('t_mandata')->nullable();
            $table->float('t_ritorno')->nullable();
            $table->float('t_diff')->nullable();
            $table->float('lettura_ausiliaria_1')->nullable();
            $table->float('lettura_ausiliaria_2')->nullable();
            $table->float('lettura_ausiliaria_consumo_1')->nullable();
            $table->float('lettura_ausiliaria_consumo_2')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letture');
    }
};
