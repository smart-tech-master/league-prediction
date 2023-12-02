<?php

namespace App\Console\Commands\ApiFootball;

use App\Services\ApiFootball\FixtureService;
use Illuminate\Console\Command;

class Fixtures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-football:fixtures {--refresh=} {--restore} {--updatePrediction}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the list of available fixtures.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(FixtureService $fixtureService)
    {
        if($this->option('refresh')){
            $fixtureService->refresh($this->option('refresh'));
        }elseif($this->option('restore')){
            $fixtureService->restore();
        }elseif($this->option('updatePrediction')){
            $fixtureService->updatePrediction();
        }else{
            $fixtureService->init();
        }
    }
}
