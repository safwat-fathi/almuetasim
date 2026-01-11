<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class CategoryService
{
    /**
     * Get all categories
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCategories()
    {
        return (new \App\Services\CacheService())->getAllCategories();
    }

    /**
     * Get category by slug
     * 
     * @param string $slug
     * @return \App\Models\Category
     */
    public function getCategoryBySlug(string $slug)
    {
        return (new \App\Services\CacheService())->getCategoryBySlug($slug);
    }

    /**
     * Validate category data
     * 
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    public function validateCategoryData(array $data): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Create a new category
     * 
     * @param array $data
     * @return \App\Models\Category
     */
    public function createCategory(array $data)
    {
        $validatedData = $this->validateCategoryData($data);
        
        // Generate slug if not provided
        if (empty($validatedData['slug'])) {
            $validatedData['slug'] = Str::slug($validatedData['name']);
        }
        
        return Category::create($validatedData);
    }

    /**
     * Update an existing category
     * 
     * @param \App\Models\Category $category
     * @param array $data
     * @return \App\Models\Category
     */
    public function updateCategory(Category $category, array $data)
    {
        $validatedData = $this->validateCategoryData($data);
        
        $category->update($validatedData);
        
        return $category;
    }

    /**
     * Delete a category
     * 
     * @param \App\Models\Category $category
     * @return bool
     */
    public function deleteCategory(Category $category)
    {
        // Check if category has associated products
        if ($category->products()->count() > 0) {
            throw new \Exception('Cannot delete category with associated products');
        }

        return $category->delete();
    }

    /**
     * Search categories
     * 
     * @param string $query
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchCategories(string $query, int $limit = 10)
    {
        return Category::where('name', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->get();
    }
}