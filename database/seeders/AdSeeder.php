<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ads = [
            'id' => 1,
            'file' => '/img/launch-screen-ad.png',
            'link' => route('home'),
            'link_type' => 'external',
            'type' => 'launch-screen',
        ];

        DB::table('ads')->insert($ads);
    }
}
