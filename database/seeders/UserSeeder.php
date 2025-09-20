<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
	public function run(): void
	{
		DB::table('users')->insert([
			'name' => 'Admin User',
			'email' => 'admin@almuetasim.com',
			'password' => Hash::make('password123'), // change later
			'created_at' => now(),
			'updated_at' => now(),
		]);
	}
}
