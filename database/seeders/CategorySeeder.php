<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'description' => 'Electronic items like mobiles and laptops.'],
            ['name' => 'Furniture', 'description' => 'Household furniture and decor.'],
            ['name' => 'Clothing', 'description' => 'Men, women, and kids clothing.'],
            ['name' => 'Books', 'description' => 'Books of various genres.'],
            ['name' => 'Grocery', 'description' => 'Grocery items.'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
