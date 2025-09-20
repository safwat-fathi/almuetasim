<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
	public function run(): void
	{
		DB::table('categories')->insert([
			['name' => 'Water Filter Systems', "description" => 'Complete household water filter with 7 stages of purification.', 'created_at' => now(), 'updated_at' => now()],
			['name' => 'Replacement Filters', 'description' => 'High-quality carbon filter for removing chlorine and odors.', 'created_at' => now(), 'updated_at' => now()],
			['name' => 'Spare Parts & Accessories', "description" => 'Parts and accessories for water filter systems.', 'created_at' => now(), 'updated_at' => now()],
			['name' => 'Installation & Maintenance Services', "description" => 'Professional installation and maintenance services for water filter systems.', 'created_at' => now(), 'updated_at' => now()],
		]);
	}
}
