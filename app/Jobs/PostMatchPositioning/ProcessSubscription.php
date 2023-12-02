<?php

namespace App\Jobs\PostMatchPositioning;

use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Season;
use App\Models\User;
use App\Services\PostMatchPositioningService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessSubscription implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60000;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $user, $league, $season;

    public function __construct(User $user, League $league, Season $season)
    {
        $this->user = $user;
        $this->league = $league;
        $this->season = $season;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new PostMatchPositioningService())->processSubscription($this->user, $this->league, $this->season);
    }

    public function failed(\Exception $exception)
    {
        \Log::debug('failed-subscription:' . $exception->getMessage());
    }
}
