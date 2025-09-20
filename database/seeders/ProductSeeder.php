<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
	public function run(): void
	{
		DB::table('products')->insert([
			[
				'sku' => 'WF-001',
				'title' => '7-Stage Water Filter System',
				'description' => 'Complete household water filter with 7 stages of purification.',
				'specs' => json_encode(['stages' => 7, 'capacity' => '200L/day']),
				'price' => 2500,
				'stock' => 10,
				'is_part' => false,
				'warranty_months' => 24,
				'images' => json_encode(['/images/products/wf-001.jpg']),
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'sku' => 'RF-101',
				'title' => 'Carbon Replacement Cartridge',
				'description' => 'High-quality carbon filter for removing chlorine and odors.',
				'specs' => json_encode(['lifetime' => '6 months']),
				'price' => 200,
				'stock' => 50,
				'is_part' => true,
				'warranty_months' => 0,
				'images' => json_encode(['/images/products/rf-101.jpg']),
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'sku' => 'RF-102',
				'title' => 'Sediment Cartridge',
				'description' => 'Removes dust, rust, and other particles from water.',
				'specs' => json_encode(['lifetime' => '3-6 months']),
				'price' => 150,
				'stock' => 60,
				'is_part' => true,
				'warranty_months' => 0,
				'images' => json_encode(['/images/products/rf-102.jpg']),
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'sku' => 'RF-103',
				'title' => 'UV Cartridge',
				'description' => 'Protects water from UV damage and bacteria.',
				'specs' => json_encode(['lifetime' => '1-2 years']),
				'price' => 300,
				'stock' => 40,
				'is_part' => true,
				'warranty_months' => 0,
				'images' => json_encode(['/images/products/rf-103.jpg']),
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'sku' => 'RF-104',
				'title' => 'Chlorine Cartridge',
				'description' => 'Removes chlorine from water.',
				'specs' => json_encode(['lifetime' => '1-2 years']),
				'price' => 250,
				'stock' => 30,
				'is_part' => true,
				'warranty_months' => 0,
				'images' => json_encode(['/images/products/rf-104.jpg']),
				'created_at' => now(),
				'updated_at' => now(),
			]
		]);
	}
}
