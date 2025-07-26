<?php

namespace App\Services;

use Cloudinary\Cloudinary;

class CloudinaryUploaderService implements ICloudinaryUploaderService
{
    private Cloudinary $cloudinary;

    public function __construct(Cloudinary $cloudinary)
    {   
        $this->cloudinary = $cloudinary;
    }

 
    public function upload(string $pathOrUrl, string $folder): string
    {
        $result = $this->cloudinary->uploadApi()->upload($pathOrUrl, ['folder' => $folder]);
        return $result['secure_url'] ?? '';
    }
}