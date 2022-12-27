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
                'brand' => 'Apple',
                'model' => 'Iphone 8',
            ],
            [
                'brand' => 'Apple',
                'model' => 'Iphone X',
            ],
            [
                'brand' => 'Apple',
                'model' => 'Iphone 11',
            ],
            [
                'brand' => 'Samsung',
                'model' => 'S22',
            ],
            [
                'brand' => 'Samsung',
                'model' => 'Galaxy fold',
            ],
            [
                'brand' => 'One plus',
                'model' => '9 pro',
            ],
        ]);
    }
}
