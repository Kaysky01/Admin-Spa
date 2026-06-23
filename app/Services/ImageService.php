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
    protected $manager = null;

    public function __construct()
    {
<<<<<<< HEAD
=======
        // Support both Intervention Image v2 and v3
        if (class_exists(\Intervention\Image\ImageManager::class)) {
            try {
                if (class_exists(\Intervention\Image\Drivers\Gd\Driver::class)) {
                    // Version 3 Gd initialization
                    $this->manager = new \Intervention\Image\ImageManager(
                        new \Intervention\Image\Drivers\Gd\Driver()
                    );
                } else {
                    // Version 2 Gd initialization
                    $this->manager = new \Intervention\Image\ImageManager([
                        'driver' => 'gd'
                    ]);
                }
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

        // Security: Use allowlist instead of blocklist
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'mp4', 'mov', 'avi', 'gif'];
        if (!in_array($extension, $allowedExtensions)) {
            throw new \InvalidArgumentException('File type not allowed. Allowed types: ' . implode(', ', $allowedExtensions));
        }

        // Additional Security check: Check MIME type to ensure it matches the extension
        $mime = $file->getMimeType();
        if (!str_starts_with($mime, 'image/') && !str_starts_with($mime, 'video/')) {
            throw new \InvalidArgumentException('Invalid file content.');
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
>>>>>>> 88e4640 (chore: implement OWASP security fixes)
        try {
            // Intervention Image v3
            $this->manager = new ImageManager(
                new Driver()
            );
        } catch (\Throwable $e) {
            Log::warning('Intervention Image init failed: ' . $e->getMessage());
            $this->manager = null;
        }
    }

    /**
     * Process uploaded file
     */
    public function processAndStore(
        UploadedFile $file,
        string $directory = 'reports'
    ): string {

        $extension = strtolower(
            $file->getClientOriginalExtension()
        );

        $filename = time() . '_' . Str::random(8) . '.' . $extension;

        // Block dangerous files
        $dangerousExtensions = [
            'php',
            'phtml',
            'exe',
            'sh',
            'bat',
            'js',
            'html'
        ];

        if (in_array($extension, $dangerousExtensions)) {
            throw new \InvalidArgumentException(
                'File type not allowed.'
            );
        }

        // Compress image
        if ($this->isImage($file) && $this->manager) {
            return $this->compressAndStore(
                $file,
                $directory,
                $filename
            );
        }

        // Non image
        return $file->storeAs(
            $directory,
            $filename,
            'public'
        );
    }

    /**
     * Compress and resize image
     */
    protected function compressAndStore(
        UploadedFile $file,
        string $directory,
        string $filename
    ): string {

        try {

            // Ensure folder exists
            $storagePath = storage_path(
                "app/public/{$directory}"
            );

            if (!is_dir($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

            $fullPath = "{$storagePath}/{$filename}";

            // Read image
            $image = $this->manager->read(
                $file->getRealPath()
            );

            // Resize max width 1280
            $image->scaleDown(width: 1280);

            // Convert + compress jpg
            $image
                ->toJpeg(quality: 70)
                ->save($fullPath);

            return "{$directory}/{$filename}";

        } catch (\Throwable $e) {

            Log::warning(
                'Image compression failed, storing raw',
                [
                    'error' => $e->getMessage()
                ]
            );

            // fallback original upload
            return $file->storeAs(
                $directory,
                $filename,
                'public'
            );
        }
    }

    /**
     * Delete files
     */
    public function deleteFiles(array $paths): void
    {
        foreach ($paths as $path) {

            if (
                $path &&
                Storage::disk('public')->exists($path)
            ) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    /**
     * Check image mime
     */
    protected function isImage(
        UploadedFile $file
    ): bool {

        return str_starts_with(
            $file->getMimeType(),
            'image/'
        );
    }
}
