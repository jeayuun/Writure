<?php

if (!function_exists('generateUniqueFilePath')) {
    /**
     * Generates a unique file path.
     *
     * @param string $folderPath  The folder to save to (e.g., 'uploads/images')
     * @param string $fileName    The file name (e.g., 'photo')
     * @param string $extension   The file extension (e.g., 'webp')
     * @return string             The full file path (e.g., '/var/www/public/uploads/images/photo_1.webp')
     */
    function generateUniqueFilePath($folderPath, $fileName, $extension)
    {
        // Create the folder if it doesn't exist
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        // Full path for the initial check
        $baseName = $fileName . '.' . $extension;
        $fullPath = $folderPath . DIRECTORY_SEPARATOR . $baseName;

        // If the file name conflicts, add a number
        $counter = 1;
        while (file_exists($fullPath)) {
            $newName = $fileName . '_' . $counter;
            $fullPath = $folderPath . DIRECTORY_SEPARATOR . $newName . '.' . $extension;
            $counter++;
        }

        return $fullPath;
    }
}