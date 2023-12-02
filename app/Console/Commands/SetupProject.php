<?php

namespace App\Console\Commands;

use App\Services\ProjectSetupService;
use Illuminate\Console\Command;

class SetupProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Running migration, seeding etc';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach ((new ProjectSetupService())->migration() as $migration){
            $this->call('migrate', ['--path' => $migration]);
        }
        $this->call('db:seed');
        //$this->call('api-football:fixtures');
    }
}
