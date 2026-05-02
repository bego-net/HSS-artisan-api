<?php

namespace App\Services;

use Cloudinary\Cloudinary;

class CloudinaryService
{
    protected Cloudinary $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
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
        $result = $this->cloudinary->uploadApi()->upload($filePath, [
            'folder' => $folder,
        ]);

        return [
            'url'       => $result['secure_url'],
            'public_id' => $result['public_id'],
        ];
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

        $this->cloudinary->uploadApi()->destroy($publicId);
    }
}
