<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
            [
                'type' => 'terms-of-service',
                'icon' => 'concierge-bell',
                'title' => 'Terms and Conditions',
                'content' => null,
            ],
            [
                'type' => 'privacy-policy',
                'icon' => 'user-shield',
                'title' => 'Privacy Policy',
                'content' => null,
            ],
            [
                'type' => 'contact-us',
                'icon' => 'phone-volume',
                'title' => 'Contact Us',
                'content' => null,
            ],
            [
                'type' => 'about-us',
                'icon' => 'info',
                'title' => 'About Us',
                'content' => null,
            ],
        ];

        DB::table('pages')->insert($pages);
    }
}
