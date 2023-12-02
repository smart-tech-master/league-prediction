<?php

namespace App\Console\Commands\ApiFootball;

use App\Models\ApiFootball\Fixture;
use Illuminate\Console\Command;

class FetchCountries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-football:countries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $apiFootball = new \ApiFootball();
        $countries = collect($apiFootball::countries()->object()->response ?? []);

        foreach (Fixture::whereLeagueId(1)->get() as $fixture){
            foreach ($fixture->teams()->get() as $team){
                $team->flag = $countries->firstWhere('name', $team->name)->flag ?? null;
                $team->save();
            }
        }
    }
}
