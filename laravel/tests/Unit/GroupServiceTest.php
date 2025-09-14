<?php

namespace Tests\Unit;

use App\Models\Group;
use App\Models\Product;
use App\Services\GroupService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupServiceTest extends TestCase
{
    use RefreshDatabase;

    protected GroupService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new GroupService();
    }

    /** @test */
    public function getAllSubgroupIds_returns_all_nested_subgroup_ids()
    {
        // Создаём иерархию:
        // Parent (1)
        // ├── Child (2)
        // │   └── Grandchild (3)
        // └── Child2 (4)

        $parent = Group::factory()->create(['id' => 1, 'id_parent' => 0]);
        $child1 = Group::factory()->create(['id' => 2, 'id_parent' => 1]);
        $grandchild = Group::factory()->create(['id' => 3, 'id_parent' => 2]);
        $child2 = Group::factory()->create(['id' => 4, 'id_parent' => 1]);

        $ids = $this->service->getAllSubgroupIds(1);

        $this->assertIsArray($ids);
        $this->assertCount(3, $ids);
        $this->assertContains(2, $ids);
        $this->assertContains(3, $ids);
        $this->assertContains(4, $ids);
    }
    /** @test */
    public function test_getTopGroups_returns_groups_with_correct_product_count_including_subgroups()
    {
        $top = Group::factory()->create(['id' => 1, 'id_parent' => 0]);
        $sub1 = Group::factory()->create(['id' => 2, 'id_parent' => 1]);
        $sub2 = Group::factory()->create(['id' => 3, 'id_parent' => 1]);

        Product::factory()->count(1)->create(['id_group' => 1]);
        Product::factory()->count(2)->create(['id_group' => 2]);
        Product::factory()->count(1)->create(['id_group' => 3]);

        $result = $this->service->getTopGroups();

        $group = $result->first();
        $this->assertEquals(4, $group->product_count); // ✅ теперь 4
    }

    /** @test */
    public function applySorting_applies_price_asc_sorting_correctly()
    {
        $query = Product::query();
        $this->service->applySorting($query, 'price_asc');
        $sql = $query->toSql();

        $this->assertStringContainsString(
            'order by (select "price" from "prices" where "prices"."id_product" = "products"."id") asc',
            $sql
        );
    }

    /** @test */
    public function applySorting_applies_price_desc_sorting_correctly()
    {
        $query = Product::query();
        $this->service->applySorting($query, 'price_desc');
        $sql = $query->toSql();

        $this->assertStringContainsString(
            'order by (select "price" from "prices" where "prices"."id_product" = "products"."id") desc',
            $sql
        );
    }

    public function applySorting_applies_name_desc_sorting()
    {
        $query = Product::query();
        $this->service->applySorting($query, 'name_desc');
        $sql = $query->toSql();

        $this->assertStringContainsString('order by "name" desc', $sql);
    }

    /** @test */
    public function applySorting_applies_default_name_asc_sorting()
    {
        $query = Product::query();
        $this->service->applySorting($query, 'invalid_sort');
        $sql = $query->toSql();

        $this->assertStringContainsString('order by "name" asc', $sql);
    }

    /** @test */
    public function getBreadcrumbsForGroup_returns_correct_path_from_root_to_group()
    {
        // Иерархия:
        // Electronics (1) → Laptops (2) → Gaming (3)

        Group::factory()->create(['id' => 1, 'id_parent' => 0, 'name' => 'Electronics']);
        Group::factory()->create(['id' => 2, 'id_parent' => 1, 'name' => 'Laptops']);
        Group::factory()->create(['id' => 3, 'id_parent' => 2, 'name' => 'Gaming']);

        $breadcrumbs = $this->service->getBreadcrumbsForGroup(3);

        $this->assertCount(3, $breadcrumbs);
        $this->assertEquals('Electronics', $breadcrumbs[0]['name']);
        $this->assertEquals('Laptops', $breadcrumbs[1]['name']);
        $this->assertEquals('Gaming', $breadcrumbs[2]['name']);
    }

    /** @test */
    public function getBreadcrumbsForGroup_handles_missing_group_gracefully()
    {
        $breadcrumbs = $this->service->getBreadcrumbsForGroup(999); // не существует

        $this->assertIsArray($breadcrumbs);
        $this->assertEmpty($breadcrumbs);
    }
}