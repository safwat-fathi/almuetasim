<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Product;

class PopulateSlugsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Populate category slugs
        Category::whereNull('slug')->get()->each(function ($category) {
            $category->update(['slug' => Str::slug($category->name)]);
        });

        // Populate product slugs
        Product::whereNull('slug')->get()->each(function ($product) {
            $product->update(['slug' => Str::slug($product->title)]);
        });
    }
}
