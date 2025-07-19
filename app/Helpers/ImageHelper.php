<?php

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

if (! function_exists('convertToWebP')) {
    function convertToWebP($imageFile, $destinationPath, $quality = 70)
    {
        $mime         = $imageFile->getClientMimeType();
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];

        if (! in_array($mime, $allowedMimes)) {
            throw new \Exception('Unsupported image format: ' . $mime);
        }

        $fullPath   = public_path($destinationPath);
        $folderPath = dirname($fullPath);

        if (! file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        // Convert to WebP using Intervention ImageManager
        $manager = new ImageManager(new Driver());
        $manager->read($imageFile->getPathname())
            ->toWebp($quality)
            ->save($fullPath);
    }
}