<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            EventSeeder::class,
        ]);
        $this->call(RoleSeeder::class);
        $this->call([RoleSeeder::class, AssignRolesSeeder::class,]);
    }
    
}  