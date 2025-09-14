<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Group;
use App\Models\Product;
use App\Models\Price;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $rootGroups = Group::factory()->root()->count(5)->create();

        foreach ($rootGroups as $group) {
            Group::factory()->count(rand(1, 3))->create(['id_parent' => $group->id]);
        }

        $products = Product::factory()->count(100)->create();

        foreach ($products as $product) {
            Price::factory()->count(1)->create(['id_product' => $product->id]);
        }
    }
}
