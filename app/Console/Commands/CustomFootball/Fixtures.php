<?php

namespace App\Console\Commands\CustomFootball;

use App\Services\CustomFootball\FixtureService;
use Illuminate\Console\Command;

class Fixtures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom-football:fixtures {--refresh=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create fixtures for private cups.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(FixtureService $fixtureService)
    {
        if($this->option('refresh')){
            return $fixtureService->refresh($this->option('refresh'));
        }else {
            return $fixtureService->init();
        }
    }
}
