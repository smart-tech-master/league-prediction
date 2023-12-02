<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locales = [
            [
                'code' => 'en',
                'name' => 'English',
                'native' => 'English',
            ],
            [
                'code' => 'ar',
                'name' => 'Arabic',
                'native' => 'العربية',
            ]
        ];

        DB::table('locales')->insert($locales);
    }
}
