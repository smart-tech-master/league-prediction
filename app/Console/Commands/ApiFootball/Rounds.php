<?php

namespace App\Console\Commands\ApiFootball;

use App\Services\ApiFootball\RoundService;
use Illuminate\Console\Command;

class Rounds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-football:rounds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the list of available rounds.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(RoundService $roundService)
    {
        $roundService->init();
    }
}
