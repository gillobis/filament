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
        Schema::create('importazioni', function (Blueprint $table) {
            $table->id();
            $table->dateTime('iniziato_alle')->nullable();
            $table->dateTime('finito_alle')->nullable();
            $table->integer('letture_importate')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('importazioni');
    }
};
