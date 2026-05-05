<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Концерты', 'slug' => 'concerts', 'description' => 'Музыкальные концерты и выступления'],
            ['name' => 'Конференции', 'slug' => 'conferences', 'description' => 'Бизнес и образовательные конференции'],
            ['name' => 'Фестивали', 'slug' => 'festivals', 'description' => 'Фестивали и крупные события'],
            ['name' => 'Спортивные события', 'slug' => 'sports', 'description' => 'Спортивные матчи и соревнования'],
            ['name' => 'Образовательные', 'slug' => 'education', 'description' => 'Лекции, семинары, мастер-классы'],
            ['name' => 'Корпоративные', 'slug' => 'corporate', 'description' => 'Корпоративные мероприятия'],
            ['name' => 'Выставки', 'slug' => 'exhibitions', 'description' => 'Выставки и ярмарки'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        $this->command->info('✅ Категории успешно созданы!');
    }
}