<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lettura;
use App\Models\Impianto;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Contatore;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'g.carlevaris@cmvgroup.com',
            'password' => bcrypt('password')
        ]);

        #SAVIGLIANO
        $impianto = Impianto::create([
            'id' => '4006',
            'nome' => 'Savigliano'
        ]);

        $impianto->utenze()->create([
            'riferimento_planimetrico' => 99999,
            'nome' => 'Gradi giorno',
            'indirizzo' => '',
            'data_attivazione' => now()
        ]);

        $utenza = $impianto->utenze()->create([
            'riferimento_planimetrico' => 1234,
            'nome' => 'Ospedale Test',
            'indirizzo' => 'via test',
            'data_attivazione' => now()
        ]);

        $utenza->contatori()->createMany([
            [
                'codice' => '096',
                'sanitaria' => false,
                'impianto_id' => $impianto->id,
            ],
            [
                'codice' => '054',
                'sanitaria' => true,
                'impianto_id' => $impianto->id,
            ]
        ]);

        $utenza->contatori()->each(function ($contatore) use ($impianto) {
            for ($i = 1; $i <= 20; $i++) {
                Lettura::factory()->create([
                    'data' => now()->subDays($i),
                    'contatore_id' => $contatore->id,
                    'impianto_id' => $impianto->id,
                ]);
            }
        });


        #CHIERI
        $impianto = Impianto::create([
            'id' => '1234',
            'nome' => 'Chieri'
        ]);

        $utenza = $impianto->utenze()->create([
            'riferimento_planimetrico' => 8726,
            'nome' => 'Condominio test',
            'indirizzo' => 'via test',
            'data_attivazione' => now()
        ]);

        $utenza->contatori()->create([
            'codice' => '666',
            'sanitaria' => true,
            'impianto_id' => $impianto->id,
        ]);

        $utenza->contatori()->each(function ($contatore) use ($impianto) {
            for ($i = 1; $i <= 20; $i++) {
                Lettura::factory()->create([
                    'data' => now()->subDays($i),
                    'contatore_id' => $contatore->id,
                    'impianto_id' => $impianto->id,
                ]);
            }
        });
    }
}
