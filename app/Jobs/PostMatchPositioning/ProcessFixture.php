<?php

namespace App\Jobs\PostMatchPositioning;

use App\Models\ApiFootball\Fixture;
use App\Services\PostMatchPositioningService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessFixture implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60000;
    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $fixture;

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
        \Log::debug('process-fixture-'.$this->fixture);
        (new PostMatchPositioningService())->processFixture($this->fixture);
    }
}
