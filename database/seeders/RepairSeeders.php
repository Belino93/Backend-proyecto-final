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
            ['type' => 'Screen replacement'],
            ['type' => 'Connector replacement'],
            ['type' => 'Speaker replacement '],
            ['type' => 'Buzzer replacement '],
            ['type' => 'Battery replacement'],
            ['type' => 'Rear camera replacement'],
            ['type' => 'Front camera replacement'],
        ]);
    }
}
