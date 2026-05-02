<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Log;
use Throwable;

class CloudinaryService
{
    protected Cloudinary $cloudinary;

    public function __construct()
    {
        $url = config('cloudinary.url');

        if (! $url) {
            throw new \RuntimeException(
                'CLOUDINARY_URL is not configured. Set it in .env and run: php artisan config:clear'
            );
        }

        $this->cloudinary = new Cloudinary($url);
    }

    /**
     * Upload an image to Cloudinary.
     *
     * @param  string  $filePath  The real path of the uploaded file.
     * @param  string  $folder    The Cloudinary folder to store in.
     * @return array{url: string, public_id: string}
     */
    public function upload(string $filePath, string $folder = 'hawi'): array
    {
        try {
            $result = $this->cloudinary->uploadApi()->upload($filePath, [
                'folder'        => $folder,
                'resource_type' => 'image',
            ]);

            return [
                'url'       => $result['secure_url'],
                'public_id' => $result['public_id'],
            ];
        } catch (Throwable $e) {
            Log::error('Cloudinary upload failed: ' . $e->getMessage(), [
                'file'  => $filePath,
                'trace' => $e->getTraceAsString(),
            ]);
            throw new \RuntimeException('Image upload failed: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Delete an image from Cloudinary by its public_id.
     */
    public function destroy(?string $publicId): void
    {
        if (! $publicId) {
            return;
        }

        try {
            $this->cloudinary->uploadApi()->destroy($publicId, [
                'resource_type' => 'image',
            ]);
        } catch (Throwable $e) {
            Log::warning('Cloudinary delete failed for public_id [' . $publicId . ']: ' . $e->getMessage());
            // Don't throw — allow the DB record to still be deleted
        }
    }

    /**
     * Test the Cloudinary connection.
     */
    public function ping(): array
    {
        try {
            $result = $this->cloudinary->adminApi()->ping();
            return ['status' => 'ok', 'response' => (array) $result];
        } catch (Throwable $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
