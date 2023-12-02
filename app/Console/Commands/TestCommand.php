<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:script';

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
        $league = \App\Models\ApiFootball\League::all();

        foreach ($league as $l) {
            $logo = $l->logo;
            $l->logo = str_replace('https://admin.leaguepred.com/', 'http://development.leaguepls.com/', $logo);
            $l->save();
        }
        $this->info($league);

    }
}
