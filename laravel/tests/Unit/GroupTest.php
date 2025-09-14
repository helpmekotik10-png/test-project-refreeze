<?php

namespace Tests\Unit;
use App\Models\Group;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;

class GroupTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    /**
     * Тест: группа может иметь родителя
     */
    public function test_group_belongs_to_parent(): void
    {
        $parent = Group::factory()->create();
        $child = Group::factory()->create(['id_parent' => $parent->id]);

        $this->assertInstanceOf(Group::class, $child->parent);
        $this->assertEquals($parent->id, $child->parent->id);
    }

    /**
     * Тест: группа может иметь подгруппы
     */
    public function test_group_has_many_subgroups(): void
    {
        $parent = Group::factory()->has(
            Group::factory()->count(2), 'subgroups'
        )->create();

        $this->assertCount(2, $parent->subgroups);
        $this->assertInstanceOf(Group::class, $parent->subgroups->first());
    }

    /**
     * Тест: группа может иметь товары
     */
    public function test_group_has_many_products(): void
    {
        $group = Group::factory()->has(
            Product::factory()->count(3)
        )->create();

        $this->assertCount(3, $group->products);
        $this->assertInstanceOf(Product::class, $group->products->first());
    }

    /**
     * Тест: таблица и временные метки
     */
    public function test_group_table_and_timestamps(): void
    {
        $group = new Group();

        $this->assertEquals('groups', $group->getTable());
        $this->assertFalse($group->usesTimestamps()); // т.к. $timestamps = false
    }
}
