<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Product extends Model
{
	use HasFactory;

	protected function casts(): array
	{
		return [
			'images' => 'array',
			'specs' => 'array',
			'is_part' => 'boolean',
			'price' => 'decimal:2',
			'warranty_months' => 'integer',
		];
	}

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	protected $fillable = ['sku', 'title', 'description', 'specs', 'price', 'stock', 'is_part', 'warranty_months', 'images', 'category_id'];
}
