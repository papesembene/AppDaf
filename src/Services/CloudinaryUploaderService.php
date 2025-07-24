<?php

namespace App\Services;

use Cloudinary\Cloudinary;

class CloudinaryUploaderService
{
    private Cloudinary $cloudinary;

    public function __construct(Cloudinary $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    /**
     * Upload une image locale ou distante sur Cloudinary.
     * @param string $pathOrUrl Chemin local ou URL distante
     * @param string $folder Dossier Cloudinary
     * @return string URL sécurisée de l'image uploadée
     */
    public function upload(string $pathOrUrl, string $folder): string
    {
        $result = $this->cloudinary->uploadApi()->upload($pathOrUrl, ['folder' => $folder]);
        return $result['secure_url'] ?? '';
    }
}