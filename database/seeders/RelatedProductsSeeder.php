<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RelatedProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get product IDs by title for easy referencing
        $productIds = DB::table('products')->pluck('id', 'title')->toArray();
        
        // Define related products relationships
        // Water purification system related to its compatible replacement filters
        $relatedProducts = [
            // Water purification system is related to replacement filters
            [
                'product_id' => $productIds['نظام تنقية مياه 7 مراحل'],
                'related_product_id' => $productIds['فلتر الكربون الاستبدالي'],
            ],
            [
                'product_id' => $productIds['نظام تنقية مياه 7 مراحل'],
                'related_product_id' => $productIds['فلتر الرواسب'],
            ],
            [
                'product_id' => $productIds['نظام تنقية مياه 7 مراحل'],
                'related_product_id' => $productIds['فلتر UV'],
            ],
            [
                'product_id' => $productIds['نظام تنقية مياه 7 مراحل'],
                'related_product_id' => $productIds['فلتر الكلور'],
            ],
            // Related filters are also related to each other
            [
                'product_id' => $productIds['فلتر الكربون الاستبدالي'],
                'related_product_id' => $productIds['فلتر الرواسب'],
            ],
            [
                'product_id' => $productIds['فلتر الكربون الاستبدالي'],
                'related_product_id' => $productIds['فلتر UV'],
            ],
            [
                'product_id' => $productIds['فلتر الكربون الاستبدالي'],
                'related_product_id' => $productIds['فلتر الكلور'],
            ],
            [
                'product_id' => $productIds['فلتر الرواسب'],
                'related_product_id' => $productIds['فلتر الكربون الاستبدالي'],
            ],
            [
                'product_id' => $productIds['فلتر الرواسب'],
                'related_product_id' => $productIds['فلتر UV'],
            ],
            [
                'product_id' => $productIds['فلتر الرواسب'],
                'related_product_id' => $productIds['فلتر الكلور'],
            ],
            // Services related to water purification system
            [
                'product_id' => $productIds['نظام تنقية مياه 7 مراحل'],
                'related_product_id' => $productIds['خدمة تركيب نظام تنقية'],
            ],
            [
                'product_id' => $productIds['نظام تنقية مياه 7 مراحل'],
                'related_product_id' => $productIds['خدمة صيانة دورية'],
            ],
            // Maintenance service related to installation service
            [
                'product_id' => $productIds['خدمة تركيب نظام تنقية'],
                'related_product_id' => $productIds['خدمة صيانة دورية'],
            ],
        ];
        
        // Insert the related products relationships
        foreach ($relatedProducts as $relation) {
            // Check if relationship already exists to avoid duplicates
            $exists = DB::table('product_related_products')
                ->where('product_id', $relation['product_id'])
                ->where('related_product_id', $relation['related_product_id'])
                ->exists();
                
            if (!$exists) {
                DB::table('product_related_products')->insert([
                    'product_id' => $relation['product_id'],
                    'related_product_id' => $relation['related_product_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
