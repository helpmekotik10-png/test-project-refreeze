<?php

namespace Tests\Feature;

use App\Services\GroupService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads()
    {
        $this->instance(GroupService::class, \Mockery::mock(GroupService::class, function ($mock) {
            // Возвращаем именно Eloquent\Collection
            $mock->shouldReceive('getTopGroups')
                 ->andReturn(new Collection());

            $mock->shouldReceive('applySorting')
                 ->andReturnNull();
        }));

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('catalog.index');
    }

    public function test_home_page_has_defaults()
    {
        $this->instance(GroupService::class, \Mockery::mock(GroupService::class, function ($mock) {
            $mock->shouldReceive('getTopGroups')
                 ->andReturn(new Collection());

            $mock->shouldReceive('applySorting')
                 ->with(\Mockery::any(), 'name_asc')
                 ->andReturnNull();
        }));

        $response = $this->get('/');

        $response->assertViewHas('sort', 'name_asc');
        $response->assertViewHas('perPage', 12);
    }
}