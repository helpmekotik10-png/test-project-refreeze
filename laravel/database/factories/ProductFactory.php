<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected array $names = [
        'Смартфон',
        'Умные часы',
        'Фитнес-браслет',
        'Ремешок для часов',
        'Умное кольцо',
        'Кабель переходник',
        'SSD диск',
        'Видеокарта',
        'Оперативная память',
        'Материнская плата',
        'Процессор',
        'Блузка',
        'Рубашка',
        'Брюки',
        'Пальто',
        'Куртка',
        'Холодильник',
        'Морозильный ларь',
        'Стиральная машина',
        'Сушильная машина',
        'Посудомоечная машина',
        'Моноблок',
        'Системный блок',
    ];

    protected array $brands = [
        'Apple',
        'Xiaomi',
        'Samsung',
        'Huawei',
        'Redmi',
        'Asus',
    ];

    protected array $qualities = [
        '128 ГБ',
        '256 ГБ',
        '512 ГБ',
        'черный',
        'белый',
        'с Wi-Fi',
        'с HDMI',
        'с DVD приводом',
        'полный комплект',
    ];

    

    protected function withFaker(): \Faker\Generator
    {
        return \Faker\Factory::create('ru_RU');
    }
    
    public function definition(): array
    {
        $name = fake()->randomElement($this->names);
        $brand = fake()->randomElement($this->brands);

        // 60% — добавляем качество
        $quality = fake()->boolean(60) ? fake()->randomElement($this->qualities) : null;

        // Формируем имя
        $fullName = $name . ' ' . $brand;
        if ($quality) {
            $fullName .= ' ' . $quality;
        }

        return [
            'name' => $fullName,
            'id_group' => Group::inRandomOrder()->first()?->id ?? Group::factory(),
        ];
    }
}
