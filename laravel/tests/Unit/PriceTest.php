<?php

namespace Tests\Unit;

use App\Models\Price;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PriceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function price_belongs_to_product(): void
    {
        $product = Product::factory()->create();
        $price = Price::factory()->create(['id_product' => $product->id]);

        $this->assertInstanceOf(Product::class, $price->product);
        $this->assertEquals($product->id, $price->product->id);
    }

    /** @test */
    public function price_uses_correct_table(): void
    {
        $price = new Price();

        $this->assertEquals('prices', $price->getTable());
    }

    /** @test */
    public function price_has_no_timestamps(): void
    {
        $price = new Price();

        $this->assertFalse($price->usesTimestamps());
    }

    /** @test */
    public function price_can_be_created_with_price_value(): void
    {
        $price = Price::factory()->create(['price' => 99900]);

        $this->assertEquals(99900, $price->price);
    }
}