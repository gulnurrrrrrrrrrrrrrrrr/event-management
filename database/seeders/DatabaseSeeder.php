<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['super_admin', 'admin', 'organizer', 'user'];
        foreach ($roles as $roleName) {
            DB::table('roles')->insertOrIgnore([
                'name'       => $roleName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (!DB::table('users')->where('email', 'admin@eventmaster.kz')->exists()) {
            $userId = DB::table('users')->insertGetId([
                'name'       => 'Super Admin',
                'email'      => 'admin@eventmaster.kz',
                'password'   => Hash::make('Admin1234!'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $roleId = DB::table('roles')->where('name', 'super_admin')->value('id');

            DB::table('role_user')->insert([
                'user_id'    => $userId,
                'role_id'    => $roleId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
