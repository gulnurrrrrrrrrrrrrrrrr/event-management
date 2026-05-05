<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Супер Администратор',
                'description' => 'Полный доступ ко всей системе'
            ],
            [
                'name' => 'admin',
                'display_name' => 'Администратор',
                'description' => 'Управление всеми мероприятиями и пользователями'
            ],
            [
                'name' => 'organizer',
                'display_name' => 'Организатор',
                'description' => 'Создание и управление своими мероприятиями'
            ],
            [
                'name' => 'user',
                'display_name' => 'Пользователь',
                'description' => 'Обычный участник мероприятий'
            ],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }

        $this->command->info('Роли успешно созданы!');
    }
}