<?php

namespace Database\Seeders;

use App\Models\Avviso;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AvvisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Avviso::factory(10)->create();
    }
}
