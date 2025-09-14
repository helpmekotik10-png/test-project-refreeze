<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_product_returns_view_with_correct_data(): void
    {
        // 1. Создаём структуру данных
        $group = Group::factory()->create(['id' => 100, 'name' => 'Electronics']);
        
        $product = Product::factory()->create([
            'id' => 999,
            'name' => 'Laptop Pro',
            'id_group' => $group->id,
        ]);

        Price::factory()->create([
            'id_product' => $product->id,
            'price' => 99900,
        ]);

        // 2. Делаем GET-запрос на /product/999
        $response = $this->get("/product/{$product->id}");

        // 3. Проверяем, что ответ — успешный
        $response->assertStatus(200);

        // 4. Проверяем, что используется нужный шаблон
        $response->assertViewIs('catalog.product');

        // 5. Проверяем, что в шаблон передаются нужные переменные
        $response->assertViewHas('product');
        $response->assertViewHas('breadcrumbs');

        // 6. Проверяем содержимое product
        $viewProduct = $response->original->getData()['product'];
        $this->assertEquals('Laptop Pro', $viewProduct->name);
        $this->assertNotNull($viewProduct->price); // загружено через with('price')
        $this->assertNotNull($viewProduct->group); // загружено через with('group')

        // 7. Проверяем хлебные крошки
        $breadcrumbs = $response->original->getData()['breadcrumbs'];
        $this->assertCount(2, $breadcrumbs);
        $this->assertEquals('Electronics', $breadcrumbs[0]['name']);
        $this->assertEquals('Laptop Pro', $breadcrumbs[1]['name']);
        $this->assertNull($breadcrumbs[1]['id']); // ['id' => null, 'name' => ...]
    }

    public function test_show_product_returns_404_when_product_not_found(): void
    {
        $response = $this->get('/product/999999'); // несуществующий ID

        $response->assertStatus(404);
    }
}