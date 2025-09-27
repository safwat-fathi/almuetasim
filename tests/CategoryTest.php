<?php

namespace Tests;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_categories()
    {
        // Create some test categories
        Category::factory()->count(3)->create();
        
        $response = $this->actingAs(\App\Models\User::factory()->create())
            ->get('/admin/categories');
        
        $response->assertStatus(200)
                 ->assertViewIs('admin.categories.index');
    }

    public function test_admin_can_create_category()
    {
        $response = $this->actingAs(\App\Models\User::factory()->create())
            ->post('/admin/categories', [
                'name' => 'Test Category',
                'description' => 'Test Description'
            ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
            'description' => 'Test Description'
        ]);
    }

    public function test_admin_can_update_category()
    {
        $category = Category::factory()->create();
        
        $response = $this->actingAs(\App\Models\User::factory()->create())
            ->put("/admin/categories/{$category->id}", [
                'name' => 'Updated Category',
                'description' => 'Updated Description'
            ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('categories', [
            'name' => 'Updated Category',
            'description' => 'Updated Description'
        ]);
    }

    public function test_admin_can_delete_category()
    {
        $category = Category::factory()->create();
        
        $response = $this->actingAs(\App\Models\User::factory()->create())
            ->delete("/admin/categories/{$category->id}");
        
        $response->assertRedirect();
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id
        ]);
    }
}