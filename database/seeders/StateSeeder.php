<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Log::info('Seed device');
        DB::table('states')->insert([
            [
                'name'=>'in_queue'
            ],
            [
                'name'=>'accepted'
            ],
            [
                'name'=>'received'
            ],
            [
                'name'=>'in_repair'
            ],
            [
                'name'=>'repaired'
            ],
            [
                'name'=>'finished'
            ],
        ]);
    }
}
