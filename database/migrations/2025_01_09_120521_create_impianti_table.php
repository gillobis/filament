<?php

use App\Models\Impianto;
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
        Schema::create('impianti', function (Blueprint $table) {
            $table->string('id', 10)->primary();
            $table->string('nome');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        /* Impianto::create([
            'id' => '4006',
            'nome' => 'Savigliano',
            'slug' => 'SAV'
        ]);

        Impianto::create([
            'id' => '1234',
            'nome' => 'Chieri',
            'slug' => 'CHI'
        ]); */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('impianti');
    }
};
