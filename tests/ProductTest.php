<?php

namespace Tests;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_can_have_type()
    {
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category'
        ]);
        
        $product = Product::create([
            'title' => 'Test Product',
            'slug' => 'test-product',
            'type' => 'product',
            'category_id' => $category->id,
            'price' => 10.99
        ]);

        $this->assertEquals('product', $product->type);
        
        $service = Product::create([
            'title' => 'Test Service',
            'slug' => 'test-service',
            'type' => 'service',
            'category_id' => $category->id,
            'price' => 49.99
        ]);

        $this->assertEquals('service', $service->type);
    }

    public function test_product_can_have_related_products()
    {
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category'
        ]);
        
        $product1 = Product::create([
            'title' => 'Main Product',
            'slug' => 'main-product',
            'type' => 'product',
            'category_id' => $category->id,
            'price' => 29.99
        ]);

        $product2 = Product::create([
            'title' => 'Related Product 1',
            'slug' => 'related-product-1',
            'type' => 'product',
            'category_id' => $category->id,
            'price' => 19.99
        ]);

        $product3 = Product::create([
            'title' => 'Related Product 2',
            'slug' => 'related-product-2',
            'type' => 'product',
            'category_id' => $category->id,
            'price' => 39.99
        ]);

        // Attach related products
        $product1->relatedProducts()->attach([$product2->id, $product3->id]);

        // Reload the models to ensure fresh data
        $product1->refresh();
        $product2->refresh();
        $product3->refresh();

        // Test the relationship
        $this->assertCount(2, $product1->relatedProducts);
        $this->assertTrue($product1->relatedProducts->contains($product2));
        $this->assertTrue($product1->relatedProducts->contains($product3));

        // Test inverse relationship
        $this->assertTrue($product2->relatedToProducts->contains($product1));
        $this->assertTrue($product3->relatedToProducts->contains($product1));
    }
}