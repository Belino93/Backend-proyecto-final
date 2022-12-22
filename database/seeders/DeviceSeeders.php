<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeviceSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Log::info('Seed device');
        DB::table('devices')->insert([
            [
                'marca' => 'Apple',
                'modelo' => 'Iphone 8',
            ],
            [
                'marca' => 'Apple',
                'modelo' => 'Iphone X',
            ],
            [
                'marca' => 'Apple',
                'modelo' => 'Iphone 11',
            ],
            [
                'marca' => 'Samsung',
                'modelo' => 'S22',
            ],
            [
                'marca' => 'Samsung',
                'modelo' => 'Galaxy fold',
            ],
            [
                'marca' => 'One plus',
                'modelo' => '9 pro',
            ],
        ]);
    }
}
