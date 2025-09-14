<?php

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
{

    // Список реальных названий групп
    protected array $groupNames = [
        'Компьютеры',
        'Ноутбуки',
        'Мониторы',
        'Клавиатуры',
        'Мыши',
        'Принтеры',
        'Офисная техника',
        'Сетевое оборудование',
        'Роутеры',
        'Программное обеспечение',
        'Аксессуары',
        'Флешки и карты памяти',
        'Наушники и колонки',
        'ИБП и фильтры',
        'Комплектующие',
    ];

    protected function withFaker(): \Faker\Generator
    {
        return \Faker\Factory::create('ru_RU');
    }
    
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement($this->groupNames),
            'id_parent' => $this->faker->boolean(70)
                ? (Group::count() > 0 ? Group::inRandomOrder()->first()->id : 0)
                : 0,
        ];
    }

     public function root(): static
    {
        return $this->state(fn (array $attributes) => [
            'id_parent' => 0,
        ]);
    }
}
