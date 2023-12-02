<?php

namespace Database\Seeders;

use Database\Seeders\ApiFootball\LeagueSeeder;
use Database\Seeders\ApiFootball\RoundSeeder;
use Database\Seeders\ApiFootball\SeasonSeeder;
use Database\Seeders\ApiFootball\CountrySeeder;
use Database\Seeders\ApiFootball\StageSeeder;
use Illuminate\Database\Seeder;

class ApiFootballSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call([
            SeasonSeeder::class,
            CountrySeeder::class,
            LeagueSeeder::class,
            RoundSeeder::class,
        ]);
    }
}
