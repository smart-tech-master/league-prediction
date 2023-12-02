<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Console\Commands\ApiFootball\EndedRound;
use App\Console\Commands\ApiFootball\Fixtures;
use App\Console\Commands\ApiFootball\GetCurrentRoundFromApiFootball;
use App\Console\Commands\ApiFootball\Standings;
use App\Console\Commands\PostMatchPositioning;
use App\Console\Commands\SendRoundNotifications;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(Fixtures::class)->everySixHours();
        $schedule->command(Fixtures::class, ['--refresh=status2'])->hourly();
//
        $schedule->command(Fixtures::class, ['--refresh=status'])->everyMinute();
        $schedule->command(Fixtures::class, ['--refresh=timestamp'])->daily();
        $schedule->command(Fixtures::class, ['--refresh=timestamp2'])->everyFifteenMinutes();

        $schedule->command(Standings::class)->hourly();
//
        $schedule->command(SendRoundNotifications::class, ['--type=reminder'])->everyMinute();
        $schedule->command(SendRoundNotifications::class, ['--type=endOfRound'])->everyMinute();

        $schedule->command(PostMatchPositioning::class)->everyMinute();

        $schedule->command(\App\Console\Commands\CustomFootball\Fixtures::class)->everyMinute();
        $schedule->command(\App\Console\Commands\CustomFootball\Fixtures::class, ['--refresh=status'])->everyMinute();

        $schedule->command(GetCurrentRoundFromApiFootball::class)->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
