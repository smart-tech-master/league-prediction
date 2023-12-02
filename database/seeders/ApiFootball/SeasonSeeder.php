<?php

namespace Database\Seeders\ApiFootball;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seasons = [
            [
                'id' => 1,
                'year' => 2022
            ]
        ];

        DB::connection('api-football')->table('seasons')->insert($seasons);
    }
}
