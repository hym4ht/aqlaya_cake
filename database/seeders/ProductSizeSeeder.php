<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Database\Seeder;

class ProductSizeSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::doesntHave('productSizes')->get();

        foreach ($products as $product) {
            // Add default sizes for each product
            ProductSize::create([
                'product_id' => $product->id,
                'name' => 'Normal (15cm)',
                'additional_price' => 0,
                'stock' => $product->stock,
                'is_available' => true,
                'sort_order' => 1,
            ]);

            ProductSize::create([
                'product_id' => $product->id,
                'name' => 'Regular (20cm)',
                'additional_price' => 50000,
                'stock' => $product->stock,
                'is_available' => true,
                'sort_order' => 2,
            ]);

            ProductSize::create([
                'product_id' => $product->id,
                'name' => 'Large (25cm)',
                'additional_price' => 100000,
                'stock' => $product->stock,
                'is_available' => true,
                'sort_order' => 3,
            ]);
        }
    }
}
