<?php

namespace App\Console\Commands\ApiFootball;

use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Season;
use App\Services\ApiFootball\RoundService;
use Illuminate\Console\Command;

class GetCurrentRoundFromApiFootball extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-football:get-current-round';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get current round of a league';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach (League::all() as $league){
            (new RoundService())->getCurrentRoundFromApiFootball($league, Season::first());
        }
        return 0;
    }
}
