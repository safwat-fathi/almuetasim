<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use App\Models\Category;

class CacheService
{
    /**
     * Cache key prefix for products
     */
    private const PRODUCT_CACHE_PREFIX = 'product_';
    
    /**
     * Cache key prefix for categories
     */
    private const CATEGORY_CACHE_PREFIX = 'category_';
    
    /**
     * Cache duration in seconds (30 minutes)
     */
    private const CACHE_DURATION = 1800;
    
    /**
     * Get a product from cache or database
     * 
     * @param string $slug
     * @return mixed
     */
    public function getProductBySlug(string $slug)
    {
        $cacheKey = self::PRODUCT_CACHE_PREFIX . $slug;
        
        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($slug) {
            return Product::where('slug', $slug)
                ->optimized()
                ->withOptimizedCategory()
                ->firstOrFail();
        });
    }
    
    /**
     * Get a category from cache or database
     * 
     * @param string $slug
     * @return mixed
     */
    public function getCategoryBySlug(string $slug)
    {
        $cacheKey = self::CATEGORY_CACHE_PREFIX . $slug;
        
        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($slug) {
            return Category::where('slug', $slug)
                ->optimized()
                ->firstOrFail();
        });
    }
    
    /**
     * Get all categories with optimization
     * 
     * @return mixed
     */
    public function getAllCategories()
    {
        return Cache::remember('categories_optimized', self::CACHE_DURATION, function () {
            return Category::optimized()->get();
        });
    }
    
    /**
     * Clear product cache
     * 
     * @param string|null $slug
     * @return bool
     */
    public function clearProductCache(?string $slug = null): bool
    {
        if ($slug) {
            return Cache::forget(self::PRODUCT_CACHE_PREFIX . $slug);
        }
        
        // Clear all product caches that match the pattern
        $keys = Cache::getPrefix() . '*product_*';
        return Cache::flush(); // This is a simplified approach; in production, you'd use proper key management
    }
    
    /**
     * Clear category cache
     * 
     * @param string|null $slug
     * @return bool
     */
    public function clearCategoryCache(?string $slug = null): bool
    {
        if ($slug) {
            return Cache::forget(self::CATEGORY_CACHE_PREFIX . $slug);
        }
        
        return Cache::forget('categories_optimized');
    }
}