<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendRoundNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:round {--type=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Reminder & End-of-Round Notifications';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(NotificationService $notificationService)
    {
        if($this->option('type')){
            $notificationService->round($this->option('type'));
        }
    }
}
