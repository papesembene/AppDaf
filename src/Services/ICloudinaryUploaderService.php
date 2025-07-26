<?php

namespace App\Services;

interface ICloudinaryUploaderService
{
    /**
     * Upload une image locale ou distante sur Cloudinary.
     *
     * @param string $pathOrUrl Chemin local ou URL distante
     * @param string $folder Dossier Cloudinary
     * @return string URL sécurisée de l'image uploadée
     */
    public function upload(string $pathOrUrl, string $folder): string;
}