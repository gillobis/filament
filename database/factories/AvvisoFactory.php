<?php

namespace Database\Factories;

use App\Enums\AvvisoPrioritaEnum;
use App\Models\Lettura;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alert>
 */
class AvvisoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $lettura = Lettura::inRandomOrder()->limit(1)->first();
        return [
            'lettura_id'  => $lettura->id,
            'impianto_id' => $lettura->impianto->id,
            'tipo'        => fake()->text(30),
            'descrizione' => fake()->text(200),
            'priorita'    => rand(0, 1) ? AvvisoPrioritaEnum::WARNING :  AvvisoPrioritaEnum::ERRORE
        ];
    }
}
