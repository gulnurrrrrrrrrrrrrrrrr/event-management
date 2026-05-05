<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssignRolesSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $adminRole      = Role::where('name', 'admin')->first();
        $organizerRole  = Role::where('name', 'organizer')->first();
        $userRole       = Role::where('name', 'user')->first();

        $users = User::all();

        foreach ($users as $user) {
            if ($user->id === 1 && $superAdminRole) {
                $user->roles()->sync([$superAdminRole->id]);
                echo "Пользователь ID {$user->id} ({$user->name}) получил роль Super Admin\n";
                continue;
            }

            if ($user->id === 2 && $adminRole) {
                $user->roles()->sync([$adminRole->id]);
                echo "Пользователь ID {$user->id} получил роль Admin\n";
                continue;
            }

            if ($user->id === 3 && $organizerRole) {
                $user->roles()->sync([$organizerRole->id]);
                echo "Пользователь ID {$user->id} получил роль Organizer\n";
                continue;
            }

            if ($userRole) {
                $user->roles()->sync([$userRole->id]);
            }
        }

        echo "Роли успешно присвоены!\n";
    }
}