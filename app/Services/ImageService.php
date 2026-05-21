<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    protected ?ImageManager $manager = null;

    public function __construct()
    {
        if (class_exists(ImageManager::class)) {
            $this->manager = new ImageManager(new Driver());
        }
    }

    /**
     * Process uploaded file: sanitize, compress images, store.
     */
    public function processAndStore(UploadedFile $file, string $directory = 'reports'): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename  = time() . '_' . Str::random(8) . '.' . $extension;

        // Prevent executable uploads
        $dangerousExtensions = ['php', 'phtml', 'exe', 'sh', 'bat', 'js', 'html'];
        if (in_array($extension, $dangerousExtensions)) {
            throw new \InvalidArgumentException('File type not allowed.');
        }

        // Image files: compress + resize
        if ($this->isImage($file) && $this->manager) {
            return $this->compressAndStore($file, $directory, $filename);
        }

        // Video/other files: store as-is
        return $file->storeAs($directory, $filename, 'public');
    }

    /**
     * Compress and resize image using Intervention Image.
     */
    protected function compressAndStore(UploadedFile $file, string $directory, string $filename): string
    {
        try {
            $image = $this->manager->read($file->getRealPath());

            // Resize to max 1280px (maintains aspect ratio)
            $image->scaleDown(1280, 1280);

            // Ensure directory exists
            $storagePath = storage_path("app/public/{$directory}");
            if (!is_dir($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

            $fullPath = "{$storagePath}/{$filename}";

            // Encode with quality 70 (good balance)
            $image->save($fullPath, quality: 70);

            return "{$directory}/{$filename}";
        } catch (\Exception $e) {
            Log::warning('Image compression failed, storing raw', ['error' => $e->getMessage()]);
            return $file->storeAs($directory, $filename, 'public');
        }
    }

    /**
     * Delete media files from storage.
     */
    public function deleteFiles(array $paths): void
    {
        foreach ($paths as $path) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    protected function isImage(UploadedFile $file): bool
    {
        return str_starts_with($file->getMimeType(), 'image/');
    }
}
