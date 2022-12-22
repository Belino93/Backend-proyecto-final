<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RepairSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Log::info('Seed repairs');
        DB::table('repairs')->insert([
            ['tipo' => 'Cambio de pantalla'],
            ['tipo' => 'Cambio de conector de carga'],
            ['tipo' => 'Cambio altavoz superior'],
            ['tipo' => 'Cambio altavoz inferior'],
            ['tipo' => 'Cambio de bateria'],
            ['tipo' => 'Cambio camara trasera'],
            ['tipo' => 'Cambio camara frontal'],
        ]);
    }
}
