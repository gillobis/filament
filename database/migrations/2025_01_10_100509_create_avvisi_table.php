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
        Schema::create('avvisi', function (Blueprint $table) {
            $table->id();
            $table->string('impianto_id');
            $table->foreign('impianto_id')->references('id')->on('impianti');
            $table->foreignIdFor(App\Models\Lettura::class, 'lettura_id')->constrained();
            $table->enum('priorita', ['WARNING', 'ERRORE'])->default('WARNING');
            $table->string('tipo');
            $table->string('descrizione');
            $table->boolean('gestito')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avvisi');
    }
};
