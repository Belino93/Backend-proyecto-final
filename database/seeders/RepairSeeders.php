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
            ['type' => 'Cambio de pantalla'],
            ['type' => 'Cambio de conector de carga'],
            ['type' => 'Cambio altavoz superior'],
            ['type' => 'Cambio altavoz inferior'],
            ['type' => 'Cambio de bateria'],
            ['type' => 'Cambio camara trasera'],
            ['type' => 'Cambio camara frontal'],
        ]);
    }
}
