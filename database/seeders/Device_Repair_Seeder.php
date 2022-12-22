<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Device_Repair_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Log::info('Seed device_repair');
        DB::table('device_repair')->insert([
            [
                'device_id'=> 1,
                'repair_id'=> 1,
                "imei"=> 8654667512245,
                'user_id'=> 1,
            ],
            [
                'device_id'=> 2,
                'repair_id'=> 4,
                "imei"=> 86548127512245,
                'user_id'=> 1,
            ],
            [
                'device_id'=> 3,
                'repair_id'=> 2,
                "imei"=> 865437512245,
                'user_id'=> 2,
            ],
            [
                'device_id'=> 1,
                'repair_id'=> 6,
                "imei"=> 8654667512245,
                'user_id'=> 3,
            ]
            ]);
    }
}
