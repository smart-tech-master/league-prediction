<?php

namespace Database\Seeders\ApiFootball;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $countries = [
            [
                'id' => 1,
                'name' => 'World',
                'code' => null,
                'flag' => null,
            ],
            [
                'id' => 2,
                'name' => 'England',
                'code' => 'GB',
                'flag' => 'https://media.api-sports.io/flags/gb.svg',
            ],
        ];

        DB::connection('api-football')->table('countries')->insert($countries);
    }
}
