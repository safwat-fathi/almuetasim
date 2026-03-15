<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGalleryItemRequest;
use App\Http\Requests\UpdateGalleryItemRequest;
use App\Models\GalleryItem;
use App\Services\ImageOptimizationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function indexPublic(): View
    {
        $galleryItems = GalleryItem::query()->latest()->paginate(12);

        return view('gallery.index', compact('galleryItems'));
    }

    public function indexAdmin(): View
    {
        $galleryItems = GalleryItem::query()->latest()->paginate(10)->withQueryString();

        return view('admin.gallery.index', compact('galleryItems'));
    }

    public function show(GalleryItem $galleryItem): JsonResponse
    {
        return response()->json($galleryItem);
    }

    public function store(StoreGalleryItemRequest $request, ImageOptimizationService $imageService): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $imagePath = $this->storeGalleryImage($request->file('image'), $imageService);

        $galleryItem = GalleryItem::query()->create([
            'caption' => $validated['caption'],
            'image_path' => $imagePath,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'تمت إضافة عنصر المعرض بنجاح.',
                'galleryItem' => $galleryItem,
            ], 201);
        }

        return redirect()->route('admin.gallery.index')->with('success', 'تمت إضافة عنصر المعرض بنجاح.');
    }

    public function update(
        UpdateGalleryItemRequest $request,
        GalleryItem $galleryItem,
        ImageOptimizationService $imageService,
    ): RedirectResponse|JsonResponse {
        $validated = $request->validated();
        $galleryItem->caption = $validated['caption'];

        if ($request->hasFile('image')) {
            $newImagePath = $this->storeGalleryImage($request->file('image'), $imageService);

            if (! empty($galleryItem->image_path) && Storage::disk('public')->exists($galleryItem->image_path)) {
                Storage::disk('public')->delete($galleryItem->image_path);
            }

            $galleryItem->image_path = $newImagePath;
        }

        $galleryItem->save();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'تم تحديث عنصر المعرض بنجاح.',
                'galleryItem' => $galleryItem,
            ]);
        }

        return redirect()->route('admin.gallery.index')->with('success', 'تم تحديث عنصر المعرض بنجاح.');
    }

    public function destroy(GalleryItem $galleryItem): RedirectResponse|JsonResponse
    {
        if (! empty($galleryItem->image_path) && Storage::disk('public')->exists($galleryItem->image_path)) {
            Storage::disk('public')->delete($galleryItem->image_path);
        }

        $galleryItem->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'تم حذف عنصر المعرض بنجاح.',
            ]);
        }

        return redirect()->route('admin.gallery.index')->with('success', 'تم حذف عنصر المعرض بنجاح.');
    }

    private function storeGalleryImage(UploadedFile $file, ImageOptimizationService $imageService): string
    {
        try {
            return $imageService->optimizeAndConvertToWebP($file, 'gallery');
        } catch (\Throwable $throwable) {
            $filename = time().'_'.uniqid().'_'.$file->getClientOriginalName();

            return $file->storeAs('gallery', $filename, 'public');
        }
    }
}
