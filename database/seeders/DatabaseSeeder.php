<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (!DB::table('users')->where('email', 'admin@eventmaster.kz')->exists()) {
            DB::table('users')->insert([
                'name'       => 'Super Admin',
                'email'      => 'admin@eventmaster.kz',
                'password'   => Hash::make('Admin1234!'),
                'role'       => 'admin', 
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
