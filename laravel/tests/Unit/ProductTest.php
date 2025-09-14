<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Group;
use App\Models\Price;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function product_uses_correct_table(): void
    {
        $product = new Product();

        $this->assertEquals('products', $product->getTable());
    }

    /** @test */
    public function product_has_no_timestamps(): void
    {
        $product = new Product();

        $this->assertFalse($product->usesTimestamps());
    }

    /** @test */
    public function product_belongs_to_group(): void
    {
        $group = Group::factory()->create();
        $product = Product::factory()->create(['id_group' => $group->id]);

        $this->assertInstanceOf(Group::class, $product->group);
        $this->assertEquals($group->id, $product->group->id);
    }

    /** @test */
    public function product_has_one_price(): void
    {
        $product = Product::factory()->create();
        $price = Price::factory()->create(['id_product' => $product->id]);

        $this->assertInstanceOf(Price::class, $product->price);
        $this->assertEquals($price->id, $product->price->id);
    }

    /** @test */
    public function product_factory_creates_instance(): void
    {
        $group = Group::factory()->create(['id' => 1]); // ← создаём группу с id=1

        $product = Product::factory()->create([
            'name' => 'Laptop',
            'id_group' => $group->id,
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Laptop',
            'id_group' => $group->id,
        ]);
    }
}