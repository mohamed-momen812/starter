<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create products and attach them to categories and thier images
       
        $products = [
            ['name' => 'product 1', 'price' => 100, 'description' => 'description 1', 'stock' => 10, 'category_id' => 1],
            ['name' => 'product 2', 'price' => 200, 'description' => 'description 2', 'stock' => 20, 'category_id' => 2],
            ['name' => 'product 3', 'price' => 300, 'description' => 'description 3', 'stock' => 30, 'category_id' => 3],
            ['name' => 'product 4', 'price' => 400, 'description' => 'description 4', 'stock' => 40, 'category_id' => 4],
            ['name' => 'product 5', 'price' => 500, 'description' => 'description 5', 'stock' => 50, 'category_id' => 5],
        ];

        foreach ($products as $product) {
            $product = Product::create($product);
            $product->images()->create(['path' => 'https://via.placeholder.com/150']);
        }
    }
}
