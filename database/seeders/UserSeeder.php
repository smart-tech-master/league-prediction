<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            'id' => 1,
            'username' => 'super-admin',
            'full_name' => 'super-admin',
            'role' => 'super-admin',
            'email' => env('SUPER_ADMIN_EMAIL'),
            'password' => Hash::make(env('SUPER_ADMIN_PASSWORD')),
            'dob' => Carbon::now(),
            'created_at' => Carbon::now(),
        ];

        DB::table('users')->insert($user);
    }
}
