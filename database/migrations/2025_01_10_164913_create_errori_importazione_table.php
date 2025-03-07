

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
        Schema::create('errori_importazione', function (Blueprint $table) {
            $table->id();
            $table->foreignId('importazione_id')->constrained('importazioni');
            $table->string('file');
            $table->string('errore');
            $table->text('dettaglio');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('errori_importazione');
    }
};
