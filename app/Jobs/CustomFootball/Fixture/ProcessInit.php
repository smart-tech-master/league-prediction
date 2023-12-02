<?php

namespace App\Jobs\CustomFootball\Fixture;

use App\Models\CustomFootball\Competition;
use App\Services\CustomFootball\FixtureService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessInit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $competition;

    public function __construct(Competition $competition)
    {
        $this->competition = $competition;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new FixtureService())->processInit($this->competition);
    }
}
