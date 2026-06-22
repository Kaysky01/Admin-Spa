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
