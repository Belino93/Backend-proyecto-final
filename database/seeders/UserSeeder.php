<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Log::info('Seed users');
        DB::table('users')->insert([
            [
                'name'=>'Fran',
                'surname'=>'Cisco',
                'email'=>'fran@gmail.com',
                'password'=>'pass1234',
            ],
            [
                'name'=>'Almu',
                'surname'=>' Dena',
                'email'=>'almu@gmail.com',
                'password'=>'pass1234',
            ],
            [
                'name'=>'Abel',
                'surname'=>'Madrid',
                'email'=>'abel@gmail.com',
                'password'=>'pass1234',
            ]
            ]);
    }
}
