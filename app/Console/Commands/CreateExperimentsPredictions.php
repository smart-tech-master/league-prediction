<?php

namespace App\Console\Commands;

use App\Services\ExperimentService;
use Illuminate\Console\Command;

class CreateExperimentsPredictions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'experiments:predictions';

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
        (new ExperimentService())->createExperimentsPredictions();
    }
}
