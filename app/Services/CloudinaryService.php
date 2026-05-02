<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Exception;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    protected Cloudinary $cloudinary;

    public function __construct()
    {
        $url = config('cloudinary.url');

        if (! $url) {
            throw new Exception('CLOUDINARY_URL is not set. Add it to .env and run php artisan config:clear');
        }

        $this->cloudinary = new Cloudinary($url);
    }

    /**
     * Upload an image to Cloudinary.
     *
     * @param  string  $filePath  The real path of the uploaded file.
     * @param  string  $folder    The Cloudinary folder to store in.
     * @return array{url: string, public_id: string}
     *
     * @throws Exception
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
        } catch (Exception $e) {
            Log::error('Cloudinary upload failed: ' . $e->getMessage());
            throw new Exception('Image upload failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete an image from Cloudinary by its public_id.
     *
     * @param  string|null  $publicId
     * @return void
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
        } catch (Exception $e) {
            Log::warning('Cloudinary delete failed for public_id [' . $publicId . ']: ' . $e->getMessage());
            // Don't throw — allow the DB record to still be deleted
        }
    }
}
