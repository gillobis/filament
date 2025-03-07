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
        Schema::create('utenze', function (Blueprint $table) {
            $table->id();
            $table->integer('riferimento_planimetrico')->unique();
            $table->string('impianto_id');
            $table->foreign('impianto_id')->references('id')->on('impianti');
            $table->string('nome');
            $table->string('indirizzo');
            $table->date('data_attivazione');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utenze');
    }
};
