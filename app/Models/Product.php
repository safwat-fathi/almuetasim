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
			'discount' => 'integer',
			'warranty_months' => 'integer',
			'type' => 'string',
		];
	}

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	/**
	 * Define the many-to-many relationship for related products
	 */
	public function relatedProducts()
	{
		return $this->belongsToMany(
			self::class, 
			'product_related_products', 
			'product_id', 
			'related_product_id'
		);
	}

	/**
	 * Define the inverse relationship for products that have this product as related
	 */
	public function relatedToProducts()
	{
		return $this->belongsToMany(
			self::class, 
			'product_related_products', 
			'related_product_id', 
			'product_id'
		);
	}

	/**
	 * Get all related products (both directions)
	 * This includes both products related to this product and products this product is related to
	 */
	public function getAllRelatedProducts($limit = 8)
	{
		// Get products related to this product (where this product is the source)
		$directlyRelatedIds = $this->relatedProducts()->pluck('product_related_products.related_product_id');
		
		// Get products this product is related to (where this product is the target)
		$relatedToThisIds = $this->relatedToProducts()->pluck('product_related_products.product_id');
		
		// Combine the IDs and remove duplicates including the current product's ID
		$allRelatedIds = collect($directlyRelatedIds)->merge($relatedToThisIds)->unique()->filter(function ($id) {
			return $id != $this->id;
		});
		
		// Return the related products with category information
		if ($allRelatedIds->count() > 0) {
			return self::whereIn('id', $allRelatedIds)->with('category')->limit($limit)->get();
		}
		
		return collect(); // Return an empty collection if no related products
	}

	/**
	 * Scope to get related products with category information
	 */
	public function scopeWithRelatedProducts($query, $limit = 8)
	{
		return $query->with(['relatedProducts' => function($query) use ($limit) {
			$query->with('category')->limit($limit);
		}]);
	}

protected $fillable = ['title', 'slug', 'description', 'specs', 'price', 'discount', 'stock', 'is_part', 'warranty_months', 'images', 'category_id', 'type'];

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
