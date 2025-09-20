<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;


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

	protected $fillable = ['sku', 'title', 'slug', 'description', 'specs', 'price', 'stock', 'is_part', 'warranty_months', 'images', 'category_id'];

	/**
	 * Boot the model.
	 */
	protected static function boot()
	{
		parent::boot();

		static::creating(function ($product) {
			if (empty($product->slug)) {
				$product->slug = Str::slug($product->title);
			}
		});

		static::updating(function ($product) {
			if (empty($product->slug)) {
				$product->slug = Str::slug($product->title);
			}
		});
	}

	/**
	 * Get the route key for the model.
	 *
	 * @return string
	 */
	public function getRouteKeyName()
	{
		return 'slug';
	}
}
