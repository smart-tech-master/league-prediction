<?php

namespace App\Console\Commands;

use App\Models\Prediction;
use App\Services\PredictionService;
use Illuminate\Console\Command;

class CalculatePredictionPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prediction:calculate-points';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate prediction points.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $predictions = Prediction::get()
            ->filter(function ($prediction) {
                return !is_null($prediction->fixture->teams()->wherePivot('ground', 'home')->first()->pivot->goals) && !is_null($prediction->fixture->teams()->wherePivot('ground', 'away')->first()->pivot->goals);
            });

        //echo '<pre>';print_r($predictions);exit;
        foreach ($predictions as $prediction) {
            PredictionService::storePoints($prediction);
        }
    }
}
