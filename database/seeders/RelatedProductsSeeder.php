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
        // Get product IDs by SKU for easy referencing
        $productIds = DB::table('products')->pluck('id', 'sku')->toArray();
        
        // Define related products relationships
        // Water purification system related to its compatible replacement filters
        $relatedProducts = [
            // Water purification system (WF-001) is related to replacement filters
            [
                'product_id' => $productIds['WF-001'],
                'related_product_id' => $productIds['RF-101'],
            ],
            [
                'product_id' => $productIds['WF-001'],
                'related_product_id' => $productIds['RF-102'],
            ],
            [
                'product_id' => $productIds['WF-001'],
                'related_product_id' => $productIds['RF-103'],
            ],
            [
                'product_id' => $productIds['WF-001'],
                'related_product_id' => $productIds['RF-104'],
            ],
            // Related filters are also related to each other
            [
                'product_id' => $productIds['RF-101'],
                'related_product_id' => $productIds['RF-102'],
            ],
            [
                'product_id' => $productIds['RF-101'],
                'related_product_id' => $productIds['RF-103'],
            ],
            [
                'product_id' => $productIds['RF-101'],
                'related_product_id' => $productIds['RF-104'],
            ],
            [
                'product_id' => $productIds['RF-102'],
                'related_product_id' => $productIds['RF-101'],
            ],
            [
                'product_id' => $productIds['RF-102'],
                'related_product_id' => $productIds['RF-103'],
            ],
            [
                'product_id' => $productIds['RF-102'],
                'related_product_id' => $productIds['RF-104'],
            ],
            // Services related to water purification system
            [
                'product_id' => $productIds['WF-001'],
                'related_product_id' => $productIds['SRV-INST-01'],
            ],
            [
                'product_id' => $productIds['WF-001'],
                'related_product_id' => $productIds['SRV-MAINT-01'],
            ],
            // Maintenance service related to installation service
            [
                'product_id' => $productIds['SRV-INST-01'],
                'related_product_id' => $productIds['SRV-MAINT-01'],
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
