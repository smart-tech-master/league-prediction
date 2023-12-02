<?php

namespace App\Jobs\CustomFootball\Fixture;

use App\Models\ApiFootball\Round;
use App\Services\CustomFootball\FixtureService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $apiFootballRound;

    public function __construct(Round $round)
    {
        $this->apiFootballRound = $round;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new FixtureService())->processStatus($this->apiFootballRound);
    }
}
