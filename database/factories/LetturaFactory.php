<?php

namespace Database\Factories;

use App\Models\Contatore;
use App\Models\Impianto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lettura>
 */
class LetturaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $contatore = Contatore::with('impianto')->inRandomOrder()->limit(1)->first();
        $energia = mt_rand(10, 2000) / 10;
        $volume = mt_rand(10, 50000) / 10;
        $tMandata = mt_rand(180, 300) / 10;
        $tRitorno = mt_rand(180, $tMandata * 10) / 10;
        $tDiff = $tMandata - $tRitorno;

        return [
            'impianto_id'                  => $contatore->impianto->id,
            'contatore_id'                 => $contatore->id,
            'contatore_codice'             => $contatore->codice,
            'tipo'                         => mt_rand(1, 5),
            'data'                         => fake()->date(),
            'ora'                          => fake()->time(),
            'energia_allineata'            => $energia,
            'energia_consumo'              => $energia,
            'energia_potenza'              => 0,
            'volume_allineato'             => $volume,
            'volume_consumo'               => $volume,
            'portata'                      => 0,
            't_mandata'                    => $tMandata,
            't_ritorno'                    => $tRitorno,
            't_diff'                       => $tDiff,
            'lettura_ausiliaria_1'         => mt_rand(1000, 30000) / 10,
            'lettura_ausiliaria_2'         => mt_rand(1000, 30000) / 10,
            'lettura_ausiliaria_consumo_1' => mt_rand(1000, 30000) / 10,
            'lettura_ausiliaria_consumo_2' => mt_rand(1000, 30000) / 10,
        ];
    }
}
