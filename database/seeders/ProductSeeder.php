<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
	public function run(): void
	{
		// Get category IDs
		$categories = DB::table('categories')->pluck('id', 'name')->toArray();
		
		DB::table('products')->insert([
			[
				'title' => 'نظام تنقية مياه 7 مراحل',
				'slug' => Str::slug('نظام تنقية مياه 7 مراحل'),
				'description' => 'نظام تنقية مياه منزلي متكامل مكون من 7 مراحل تنقية.',
				'specs' => json_encode(['stages' => 7, 'capacity' => '200 لتر/يوم']),
				'price' => 2500,
				'stock' => 10,
				'is_part' => false,
				'warranty_months' => 24,
				'images' => json_encode(['/images/products/wf-001.jpg']),
				'category_id' => $categories['أنظمة تنقية المياه'] ?? null,
				'type' => 'product',
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'title' => 'فلتر الكربون الاستبدالي',
				'slug' => Str::slug('فلتر الكربون الاستبدالي'),
				'description' => 'فلتر كربون عالي الجودة لإزالة الكلور والروائح.',
				'specs' => json_encode(['lifetime' => '6 أشهر']),
				'price' => 200,
				'stock' => 50,
				'is_part' => true,
				'warranty_months' => 0,
				'images' => json_encode(['/images/products/rf-101.jpg']),
				'category_id' => $categories['فلاتر الاستبدال'] ?? null,
				'type' => 'product',
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'title' => 'فلتر الرواسب',
				'slug' => Str::slug('فلتر الرواسب'),
				'description' => 'يزيل الغبار والصدأ والجسيمات الأخرى من الماء.',
				'specs' => json_encode(['lifetime' => '3-6 أشهر']),
				'price' => 150,
				'stock' => 60,
				'is_part' => true,
				'warranty_months' => 0,
				'images' => json_encode(['/images/products/rf-102.jpg']),
				'category_id' => $categories['فلاتر الاستبدال'] ?? null,
				'type' => 'product',
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'title' => 'فلتر UV',
				'slug' => Str::slug('فلتر UV'),
				'description' => 'يحمي الماء من أضرار الأشعة فوق البنفسجية والبكتيريا.',
				'specs' => json_encode(['lifetime' => '1-2 سنوات']),
				'price' => 300,
				'stock' => 40,
				'is_part' => true,
				'warranty_months' => 0,
				'images' => json_encode(['/images/products/rf-103.jpg']),
				'category_id' => $categories['فلاتر الاستبدال'] ?? null,
				'type' => 'product',
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'title' => 'فلتر الكلور',
				'slug' => Str::slug('فلتر الكلور'),
				'description' => 'يزيل الكلور من الماء.',
				'specs' => json_encode(['lifetime' => '1-2 سنوات']),
				'price' => 250,
				'stock' => 30,
				'is_part' => true,
				'warranty_months' => 0,
				'images' => json_encode(['/images/products/rf-104.jpg']),
				'category_id' => $categories['فلاتر الاستبدال'] ?? null,
				'type' => 'product',
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'title' => 'خدمة تركيب نظام تنقية',
				'slug' => Str::slug('خدمة تركيب نظام تنقية'),
				'description' => 'خدمة تركيب احترافية لأنظمة تنقية المياه.',
				'specs' => json_encode(['service_type' => 'installation', 'duration' => '2 ساعات']),
				'price' => 500,
				'stock' => 100,
				'is_part' => false,
				'warranty_months' => 0,
				'images' => json_encode(['/images/products/srv-inst-01.jpg']),
				'category_id' => $categories['خدمات التركيب والصيانة'] ?? null,
				'type' => 'service',
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'title' => 'خدمة صيانة دورية',
				'slug' => Str::slug('خدمة صيانة دورية'),
				'description' => 'خدمة صيانة دورية لأنظمة تنقية المياه.',
				'specs' => json_encode(['service_type' => 'maintenance', 'frequency' => '6 أشهر']),
				'price' => 300,
				'stock' => 100,
				'is_part' => false,
				'warranty_months' => 0,
				'images' => json_encode(['/images/products/srv-maint-01.jpg']),
				'category_id' => $categories['خدمات التركيب والصيانة'] ?? null,
				'type' => 'service',
				'created_at' => now(),
				'updated_at' => now(),
			]
		]);
	}
}
