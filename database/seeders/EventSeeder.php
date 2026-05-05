<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        Event::create([
            'title' => 'Конференция Tech 2026',
            'description' => 'Большая конференция по современным технологиям и искусственному интеллекту.',
            'event_date' => now()->addDays(15),
            'location' => 'Бизнес-центр "Плаза"',
            'city' => 'Алматы',
            'max_participants' => 150,
            'category_id' => $categories->first()->id ?? 1,
            'image' => null,
        ]);

        Event::create([
            'title' => 'Мастер-класс по UI/UX дизайну',
            'description' => 'Практический мастер-класс от ведущих дизайнеров Казахстана.',
            'event_date' => now()->addDays(20),
            'location' => 'Art Space Hub',
            'city' => 'Астана',
            'max_participants' => 40,
            'category_id' => $categories->skip(1)->first()->id ?? 1,
            'image' => null,
        ]);

        Event::create([
            'title' => 'Бизнес-завтрак с инвесторами',
            'description' => 'Нетворкинг и поиск инвестиций для стартапов.',
            'event_date' => now()->addDays(25),
            'location' => 'Отель "Grand"',
            'city' => 'Алматы',
            'max_participants' => 30,
            'category_id' => $categories->skip(2)->first()->id ?? 1,
            'image' => null,
        ]);
    }
}