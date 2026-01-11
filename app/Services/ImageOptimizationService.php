<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

class ImageOptimizationService
{
    /**
     * Optimize and convert image to WebP format.
     *
     * @return string Path to the optimized image
     */
    public function optimizeAndConvertToWebP(UploadedFile $file, string $directory = 'uploads', ?string $filename = null): string
    {
        // Generate filename if not provided
        if (! $filename) {
            $extension = 'webp';
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = time().'_'.uniqid().'_'.$originalName.'.'.$extension;
        }

        // Create temporary file path
        $tempPath = $file->getRealPath();

        // Convert to WebP using GD or Imagick
        $webpPath = $this->convertToWebP($tempPath);

        // Store the WebP file
        $storedPath = Storage::disk('public')->putFileAs(
            $directory,
            new \Illuminate\Http\File($webpPath),
            $filename
        );

        // Optimize the WebP image
        $fullPath = Storage::disk('public')->path($storedPath);
        $this->optimizeImage($fullPath);

        // Clean up temporary WebP file if it was created separately
        if ($webpPath !== $tempPath && file_exists($webpPath)) {
            unlink($webpPath);
        }

        return $storedPath;
    }

    /**
     * Convert image to WebP format.
     *
     * @return string Path to the WebP image
     */
    private function convertToWebP(string $imagePath): string
    {
        $webpPath = sys_get_temp_dir().'/'.uniqid().'.webp';

        // Get image info
        $imageInfo = getimagesize($imagePath);
        if (! $imageInfo) {
            throw new \Exception('Invalid image file');
        }

        $mimeType = $imageInfo['mime'];
        $width = $imageInfo[0];
        $height = $imageInfo[1];

        // Create image resource based on MIME type
        switch ($mimeType) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($imagePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($imagePath);
                // Preserve transparency
                imagealphablending($image, false);
                imagesavealpha($image, true);
                break;
            case 'image/webp':
                // Already WebP, just copy
                copy($imagePath, $webpPath);

                return $webpPath;
            default:
                throw new \Exception('Unsupported image format: '.$mimeType);
        }

        if (! $image) {
            throw new \Exception('Failed to create image resource');
        }

        // Convert to WebP with quality 85 (good balance between size and quality)
        $success = imagewebp($image, $webpPath, 85);

        // Free memory
        imagedestroy($image);

        if (! $success) {
            throw new \Exception('Failed to convert image to WebP');
        }

        return $webpPath;
    }

    /**
     * Optimize an image file.
     */
    private function optimizeImage(string $imagePath): void
    {
        try {
            // Use Spatie's image optimizer if available
            ImageOptimizer::optimize($imagePath);
        } catch (\Exception $e) {
            // If optimization fails, continue without it
            // The WebP conversion already provides good compression
        }
    }

    /**
     * Optimize existing image without conversion.
     */
    public function optimizeExistingImage(string $imagePath): void
    {
        $fullPath = Storage::disk('public')->path($imagePath);
        if (file_exists($fullPath)) {
            $this->optimizeImage($fullPath);
        }
    }

    /**
     * Convert existing image to WebP.
     *
     * @return string|null Path to the new WebP image or null if conversion failed
     */
    public function convertExistingToWebP(string $imagePath): ?string
    {
        $fullPath = Storage::disk('public')->path($imagePath);
        if (! file_exists($fullPath)) {
            return null;
        }

        try {
            $webpPath = $this->convertToWebP($fullPath);
            $newFilename = pathinfo($imagePath, PATHINFO_FILENAME).'.webp';
            $newPath = dirname($imagePath).'/'.$newFilename;

            // Move WebP to storage
            Storage::disk('public')->put($newPath, file_get_contents($webpPath));

            // Clean up temporary file
            if (file_exists($webpPath)) {
                unlink($webpPath);
            }

            // Optimize the new WebP
            $this->optimizeImage(Storage::disk('public')->path($newPath));

            return $newPath;
        } catch (\Exception $e) {
            return null;
        }
    }
}
