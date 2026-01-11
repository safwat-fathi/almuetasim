<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\ImageOptimizationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ConvertImagesToWebP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:convert-to-webp {--force : Force conversion even if WebP exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert all product images to WebP format';

    /**
     * Execute the console command.
     */
    public function handle(ImageOptimizationService $imageService)
    {
        $products = Product::all();
        $bar = $this->output->createProgressBar(count($products));
        $bar->start();

        $convertedCount = 0;
        $errorCount = 0;

        foreach ($products as $product) {
            $images = $product->images ?? [];
            $updatedImages = [];
            $hasChanges = false;

            if (empty($images)) {
                $bar->advance();
                continue;
            }

            foreach ($images as $imagePath) {
                // Skip if already WebP and not forced
                if (str_ends_with(strtolower($imagePath), '.webp') && ! $this->option('force')) {
                    $updatedImages[] = $imagePath;
                    continue;
                }

                // Skip remote URLs
                if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
                    $updatedImages[] = $imagePath;
                    continue;
                }

                // Clean path
                $cleanPath = ltrim($imagePath, '/');
                
                // Check if file exists in public directory (old images location)
                $publicPath = public_path($cleanPath);
                
                if (file_exists($publicPath)) {
                    // Convert images in public directory
                    try {
                        $newPath = $this->convertPublicImage($publicPath, $cleanPath);
                        
                        if ($newPath) {
                            $updatedImages[] = '/' . $newPath;
                            $hasChanges = true;
                            $convertedCount++;
                        } else {
                            $updatedImages[] = $imagePath;
                            $this->newLine();
                            $this->warn("Could not convert: {$imagePath}");
                            $errorCount++;
                        }
                    } catch (\Exception $e) {
                        $updatedImages[] = $imagePath;
                        $this->newLine();
                        $this->error("Error converting {$imagePath}: " . $e->getMessage());
                        $errorCount++;
                    }
                } else {
                    // Try storage disk
                    $storagePath = $cleanPath;
                    if (str_starts_with($storagePath, 'storage/')) {
                        $storagePath = substr($storagePath, 8);
                    }

                    try {
                        $newPath = $imageService->convertExistingToWebP($storagePath);
                        
                        if ($newPath) {
                            $updatedImages[] = $newPath;
                            $hasChanges = true;
                            $convertedCount++;
                        } else {
                            $updatedImages[] = $imagePath;
                            $this->newLine();
                            $this->warn("Could not find/convert: {$imagePath}");
                            $errorCount++;
                        }
                    } catch (\Exception $e) {
                        $updatedImages[] = $imagePath;
                        $this->newLine();
                        $this->error("Error converting {$imagePath}: " . $e->getMessage());
                        $errorCount++;
                    }
                }
            }

            // Save new image paths to DB if changed
            if ($hasChanges) {
                $product->images = $updatedImages;
                $product->saveQuietly(); // Don't trigger updated events
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        
        $this->info("Conversion completed!");
        $this->info("Converted images: {$convertedCount}");
        
        if ($errorCount > 0) {
            $this->error("Errors encountered: {$errorCount}");
        }
        
        return 0;
    }

    /**
     * Convert an image in the public directory
     */
    private function convertPublicImage(string $fullPath, string $relativePath): ?string
    {
        if (!file_exists($fullPath)) {
            return null;
        }

        // Get image info
        $imageInfo = @getimagesize($fullPath);
        if (!$imageInfo) {
            return null;
        }

        $mimeType = $imageInfo['mime'];

        // Skip if already WebP
        if ($mimeType === 'image/webp') {
            return $relativePath;
        }

        // Create image resource based on MIME type
        $image = match($mimeType) {
            'image/jpeg' => @imagecreatefromjpeg($fullPath),
            'image/png' => @imagecreatefrompng($fullPath),
            default => null
        };

        if (!$image) {
            return null;
        }

        // Preserve transparency for PNG
        if ($mimeType === 'image/png') {
            imagealphablending($image, false);
            imagesavealpha($image, true);
        }

        // Generate new WebP path
        $pathInfo = pathinfo($relativePath);
        $newPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
        $newFullPath = public_path($newPath);

        // Ensure directory exists
        $dir = dirname($newFullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Convert to WebP
        $success = imagewebp($image, $newFullPath, 85);
        imagedestroy($image);

        return $success ? $newPath : null;
    }
}
