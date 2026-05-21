<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    protected $manager = null;

    public function __construct()
    {
        // Only load Intervention Image if installed (prevents crash on VPS without it)
        if (class_exists(\Intervention\Image\ImageManager::class)
            && class_exists(\Intervention\Image\Drivers\Gd\Driver::class)) {
            try {
                $this->manager = new \Intervention\Image\ImageManager(
                    new \Intervention\Image\Drivers\Gd\Driver()
                );
            } catch (\Throwable $e) {
                Log::warning('Intervention Image init failed: ' . $e->getMessage());
                $this->manager = null;
            }
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

        // Image files: compress + resize (if Intervention available)
        if ($this->isImage($file) && $this->manager) {
            return $this->compressAndStore($file, $directory, $filename);
        }

        // Fallback: store as-is
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
