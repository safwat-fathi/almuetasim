<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
	public function run(): void
	{
		DB::table('categories')->insert([
			['name' => 'أنظمة تنقية المياه', 'slug' => Str::slug('أنظمة تنقية المياه'), "description" => 'أنظمة تنقية مياه منزلية متكاملة مكونة من 7 مراحل تنقية.', 'created_at' => now(), 'updated_at' => now()],
			['name' => 'فلاتر الاستبدال', 'slug' => Str::slug('فلاتر الاستبدال'), 'description' => 'فلتر كربون عالي الجودة لإزالة الكلور والروائح.', 'created_at' => now(), 'updated_at' => now()],
			['name' => 'قطع الغيار والإكسسوارات', "slug" => Str::slug('قطع الغيار والإكسسوارات'), "description" => 'قطع الغيار والملحقات لأنظمة تنقية المياه.', 'created_at' => now(), 'updated_at' => now()],
			['name' => 'خدمات التركيب والصيانة', "slug" => Str::slug('خدمات التركيب والصيانة'), "description" => 'خدمات تركيب وصيانة احترافية لأنظمة تنقية المياه.', 'created_at' => now(), 'updated_at' => now()],
		]);
	}
}
