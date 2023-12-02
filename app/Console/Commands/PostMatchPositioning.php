<?php

namespace App\Console\Commands;

use App\Jobs\PostMatchPositioning\ProcessEndedRound;
use App\Jobs\PostMatchPositioning\ProcessFixture;
use App\Jobs\PostMatchPositioning\ProcessPredictionPoints;
use App\Services\ApiFootball\FixtureService;
use App\Services\PostMatchPositioningService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class PostMatchPositioning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'match:position';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set position after the match is finished';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        $fixtures = (new PostMatchPositioningService())->getFixtures();
        foreach ($fixtures as $fixture) {
            (new PostMatchPositioningService())->processPredictionPoints($fixture);
            ProcessPredictionPoints::dispatch($fixture);
            ProcessFixture::dispatch($fixture);
            ProcessEndedRound::dispatch($fixture);
        }

        (new PostMatchPositioningService())->execUpdatePoint();
    }

    public function handleBackUp()
    {
        //(new PostMatchPositioningService())->init();
        $fixtures = (new PostMatchPositioningService())->getFixtures();
        foreach ($fixtures as $fixture) {
            (new PostMatchPositioningService())->processPredictionPoints($fixture);
            ProcessPredictionPoints::dispatch($fixture);
            ProcessFixture::dispatch($fixture);
            ProcessEndedRound::dispatch($fixture);
//            Bus::chain([
//                new ProcessPredictionPoints($fixture),
//                new ProcessFixture($fixture),
//                new ProcessEndedRound($fixture),
//            ])->dispatch();
        }

        \Log::info('match:position');
    }
}
