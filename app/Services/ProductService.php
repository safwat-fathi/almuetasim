<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class ProductService
{
    /**
     * Get paginated products with optional filters
     * 
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getProducts(array $filters = [], int $perPage = 20)
    {
        $query = Product::with('category');

        // Apply search filter
        if (!empty($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where('title', 'LIKE', "%{$searchTerm}%");
        }

        // Apply category filter
        if (!empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        // Apply status filter
        if (!empty($filters['status'])) {
            switch ($filters['status']) {
                case 'active':
                    $query->where('stock', '>', 0);
                    break;
                case 'inactive':
                    $query->where('stock', 0);
                    break;
                case 'low-stock':
                    $query->where('stock', '>', 0)->where('stock', '<=', 10);
                    break;
            }
        }

        // Apply sorting
        $sort = $filters['sort'] ?? 'newest';
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'featured':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query->paginate($perPage);
    }

    /**
     * Get a product by slug
     * 
     * @param string $slug
     * @return \App\Models\Product
     */
    public function getProductBySlug(string $slug)
    {
        return (new \App\Services\CacheService())->getProductBySlug($slug);
    }

    /**
     * Validate product data
     * 
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    public function validateProductData(array $data): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'specs' => 'nullable|json',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|integer|min:0|max:100',
            'stock' => 'required|integer|min:0',
            'is_part' => 'required|boolean',
            'warranty_months' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'type' => 'required|in:product,service',
        ];

        // Validate the data
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Create a new product
     * 
     * @param array $data
     * @return \App\Models\Product
     */
    public function createProduct(array $data)
    {
        $validatedData = $this->validateProductData($data);
        
        // Process specs as array
        $validatedData['specs'] = json_decode($data['specs'] ?? '[]', true) ?: [];
        
        // Generate slug if not provided
        if (empty($validatedData['slug'])) {
            $validatedData['slug'] = Str::slug($validatedData['title']);
        }

        // Remove SKU if it exists (according to your codebase)
        unset($validatedData['sku']);

        $product = Product::create($validatedData);
        
        // Reload with relations
        $product->load('category');
        
        return $product;
    }

    /**
     * Update an existing product
     * 
     * @param \App\Models\Product $product
     * @param array $data
     * @return \App\Models\Product
     */
    public function updateProduct(Product $product, array $data)
    {
        $validatedData = $this->validateProductData($data);
        
        // Process specs as array
        $validatedData['specs'] = json_decode($data['specs'] ?? '[]', true) ?: [];
        
        // Update the product
        $product->update($validatedData);
        
        // Reload with relations
        $product->refresh()->load('category');
        
        return $product;
    }

    /**
     * Get related products
     * 
     * @param \App\Models\Product $product
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRelatedProducts(Product $product, int $limit = 8)
    {
        return $product->getAllRelatedProducts($limit);
    }

    /**
     * Search products
     * 
     * @param string $query
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchProducts(string $query, int $limit = 10)
    {
        return Product::where('title', 'LIKE', "%{$query}%")
            ->with('category')
            ->limit($limit)
            ->get();
    }
}