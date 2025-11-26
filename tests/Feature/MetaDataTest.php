<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Category;

class MetaDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_page_renders_title_and_meta_description()
    {
        $category = Category::create(['name' => 'Cat 1', 'slug' => 'cat-1']);

        $product = Product::create([
            'title' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'This is a test product description.',
            'price' => 9.99,
            'category_id' => $category->id,
            'type' => 'product'
        ]);

        $response = $this->get(route('product.show', $product->slug));

        $response->assertStatus(200);
        $response->assertSee('<title>Test Product', false);
        $response->assertSee('<meta name="description" content="This is a test product description."', false);
    }
}
