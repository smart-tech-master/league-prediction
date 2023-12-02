<?php

namespace Database\Seeders\ApiFootball;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeagueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $leagues = [
            [
                'id' => 1,
                'name' => 'International Cup',
                'type' => 'Cup',
                'logo' => asset('img/leagues/1.jpeg'),
                'country_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Champions',
                'type' => 'Cup',
                'logo' => asset('img/leagues/2.jpeg'),
                'country_id' => 1,
            ],
            [
                'id' => 39,
                'name' => 'English League',
                'type' => 'League',
                'logo' => asset('img/leagues/39.jpeg'),
                'country_id' => 2,
            ],
        ];

        DB::connection('api-football')->table('leagues')->insert($leagues);
    }
}
