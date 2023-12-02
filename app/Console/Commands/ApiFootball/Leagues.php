<?php

namespace App\Console\Commands\ApiFootball;

use App\Services\ApiFootball\LeagueService;
use Illuminate\Console\Command;

class Leagues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-football:leagues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the list of available leagues.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(LeagueService $reagueService)
    {
        $reagueService->init();
        return Command::SUCCESS;
    }
}
