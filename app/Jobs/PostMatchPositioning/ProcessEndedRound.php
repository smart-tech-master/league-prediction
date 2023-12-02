<?php

namespace App\Jobs\PostMatchPositioning;

use App\Models\ApiFootball\Fixture;
use App\Services\ApiFootball\RoundService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessEndedRound implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $fixture;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Fixture $fixture)
    {
        $this->fixture = $fixture;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new RoundService())->processEndedRound($this->fixture);
    }
}
