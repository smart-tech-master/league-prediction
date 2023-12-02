<?php

namespace App\Console\Commands\ApiFootball;

use App\Services\ApiFootball\StandingService;
use Illuminate\Console\Command;

class Standings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-football:standings';

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
        (new StandingService())->fetch();
    }
}
