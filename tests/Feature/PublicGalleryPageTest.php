<?php

namespace Tests\Feature;

use App\Models\GalleryItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicGalleryPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_gallery_page_is_accessible_and_contains_arabic_heading(): void
    {
        GalleryItem::factory()->create([
            'caption' => 'تركيب محطة مياه منزلية',
        ]);

        $response = $this->get(route('gallery.index'));

        $response->assertStatus(200);
        $response->assertSee('معرض أعمالنا');
        $response->assertSee('تركيب محطة مياه منزلية');
    }

    public function test_home_page_shows_only_latest_four_gallery_items_and_footer_link(): void
    {
        for ($index = 1; $index <= 6; $index++) {
            GalleryItem::factory()->create([
                'caption' => 'صورة المعرض '.$index,
                'created_at' => now()->subMinutes(7 - $index),
                'updated_at' => now()->subMinutes(7 - $index),
            ]);
        }

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('معرض أعمالنا');
        $response->assertSee(route('gallery.index'));
        $response->assertSee('صورة المعرض 6');
        $response->assertSee('صورة المعرض 5');
        $response->assertSee('صورة المعرض 4');
        $response->assertSee('صورة المعرض 3');
        $response->assertDontSee('صورة المعرض 2');
        $response->assertDontSee('صورة المعرض 1');
    }
}
