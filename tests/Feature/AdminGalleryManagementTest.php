<?php

namespace Tests\Feature;

use App\Models\GalleryItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminGalleryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_gallery_routes(): void
    {
        Storage::fake('public');
        $galleryItem = GalleryItem::factory()->create();
        $token = 'test-token';

        $this->get(route('admin.gallery.index'))->assertRedirect(route('login'));
        $this->get(route('admin.gallery.show', $galleryItem))->assertRedirect(route('login'));
        $this->withSession(['_token' => $token])
            ->post(route('admin.gallery.store'), ['_token' => $token])
            ->assertRedirect(route('login'));
        $this->withSession(['_token' => $token])
            ->put(route('admin.gallery.update', $galleryItem), ['_token' => $token])
            ->assertRedirect(route('login'));
        $this->withSession(['_token' => $token])
            ->delete(route('admin.gallery.destroy', $galleryItem), ['_token' => $token])
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_create_gallery_item_with_image_and_caption(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();
        $image = UploadedFile::fake()->image('gallery.jpg');

        $response = $this->actingAs($admin)
            ->withSession(['_token' => 'test-token'])
            ->withHeader('Accept', 'application/json')
            ->post(route('admin.gallery.store'), [
                '_token' => 'test-token',
                'caption' => 'تركيب فلتر مركزي في منزل عميل',
                'image' => $image,
            ]);

        $response->assertCreated();
        $response->assertJsonPath('message', 'تمت إضافة عنصر المعرض بنجاح.');
        $this->assertDatabaseCount('gallery_items', 1);

        $galleryItem = GalleryItem::query()->first();
        $this->assertNotNull($galleryItem);
        $this->assertEquals('تركيب فلتر مركزي في منزل عميل', $galleryItem->caption);
        Storage::disk('public')->assertExists($galleryItem->image_path);
    }

    public function test_admin_cannot_create_gallery_item_without_caption(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();
        $image = UploadedFile::fake()->image('gallery.jpg');

        $response = $this->actingAs($admin)
            ->withSession(['_token' => 'test-token'])
            ->withHeader('Accept', 'application/json')
            ->post(route('admin.gallery.store'), [
                '_token' => 'test-token',
                'caption' => '',
                'image' => $image,
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['caption']);
        $this->assertDatabaseCount('gallery_items', 0);
    }

    public function test_admin_can_update_caption_and_replace_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();

        Storage::disk('public')->put('gallery/old-image.webp', 'old-image-content');
        $galleryItem = GalleryItem::factory()->create([
            'caption' => 'وصف قديم',
            'image_path' => 'gallery/old-image.webp',
        ]);

        $newImage = UploadedFile::fake()->image('new-image.jpg');

        $response = $this->actingAs($admin)
            ->withSession(['_token' => 'test-token'])
            ->withHeader('Accept', 'application/json')
            ->post(route('admin.gallery.update', $galleryItem), [
                '_token' => 'test-token',
                '_method' => 'PUT',
                'caption' => 'وصف محدث',
                'image' => $newImage,
            ]);

        $response->assertOk();
        $response->assertJsonPath('message', 'تم تحديث عنصر المعرض بنجاح.');

        $galleryItem->refresh();
        $this->assertEquals('وصف محدث', $galleryItem->caption);
        $this->assertNotEquals('gallery/old-image.webp', $galleryItem->image_path);
        Storage::disk('public')->assertMissing('gallery/old-image.webp');
        Storage::disk('public')->assertExists($galleryItem->image_path);
    }

    public function test_admin_can_delete_gallery_item_and_its_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();

        Storage::disk('public')->put('gallery/delete-me.webp', 'image-content');
        $galleryItem = GalleryItem::factory()->create([
            'image_path' => 'gallery/delete-me.webp',
        ]);

        $response = $this->actingAs($admin)
            ->withSession(['_token' => 'test-token'])
            ->withHeader('Accept', 'application/json')
            ->delete(route('admin.gallery.destroy', $galleryItem), [
                '_token' => 'test-token',
            ]);

        $response->assertOk();
        $response->assertJsonPath('message', 'تم حذف عنصر المعرض بنجاح.');

        $this->assertDatabaseMissing('gallery_items', [
            'id' => $galleryItem->id,
        ]);
        Storage::disk('public')->assertMissing('gallery/delete-me.webp');
    }
}
