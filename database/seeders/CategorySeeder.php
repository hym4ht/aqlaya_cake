<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            ['name' => 'Cakes', 'slug' => 'cakes', 'description' => 'Cake custom untuk momen spesial.'],
            ['name' => 'Bread', 'slug' => 'bread', 'description' => 'Roti segar panggang setiap hari.'],
            ['name' => 'Pastry', 'slug' => 'pastry', 'description' => 'Pastry buttery untuk sajian.'],
            ['name' => 'Dessert', 'slug' => 'dessert', 'description' => 'Dessert manis penutup hidangan.'],
        ])->mapWithKeys(fn ($category) => [
            $category['slug'] => Category::query()->create($category),
        ]);
    }
}
