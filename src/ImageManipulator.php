<?php

namespace Tigress;

use Exception;

/**
 * Class Text Manipulator (PHP version 8.5)
 *
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2026, rudymas.be. (http://www.rudymas.be/)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 2026.02.26.0
 * @package Tigress\TextManipulator
 */
class ImageManipulator
{
    /**
     * Get the version of QrCodeGenerator class.
     *
     * @return string
     */
    public static function version(): string
    {
        return '2026.02.26';
    }

    /**
     * Converts an image to a base64 string.
     *
     * @param string $imagePath
     * @return string
     */
    public function getBase64Image(string $imagePath): string
    {
        $imageData = file_get_contents($imagePath);
        $base64Image = base64_encode($imageData);
        $mimeType = mime_content_type($imagePath);
        return "data:$mimeType;base64,$base64Image";
    }

    /**
     * Resizes an image and returns it as a base64 string.
     * Note: This method requires the GD library to be installed and enabled in PHP.
     *
     * @param string $imagePath
     * @param int $newWidth
     * @param int $newHeight
     * @return string
     * @throws Exception
     */
    public function resizeImage(string $imagePath, int $newWidth = 0, int $newHeight = 0): string
    {
        $imageInfo = getimagesize($imagePath);
        $mimeType = $imageInfo['mime'];

        $sourceImage = match ($mimeType) {
            'image/jpeg' => imagecreatefromjpeg($imagePath),
            'image/png' => imagecreatefrompng($imagePath),
            'image/gif' => imagecreatefromgif($imagePath),
            default => throw new Exception('Unsupported image type: ' . $mimeType),
        };

        $originalWidth = imagesx($sourceImage);
        $originalHeight = imagesy($sourceImage);

        if ($newWidth === 0 && $newHeight === 0) {
            $newWidth = $originalWidth;
            $newHeight = $originalHeight;
        } elseif ($newWidth === 0) {
            $newWidth = (int) (($newHeight / $originalHeight) * $originalWidth);
        } elseif ($newHeight === 0) {
            $newHeight = (int) (($newWidth / $originalWidth) * $originalHeight);
        }

        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

        ob_start();
        switch ($mimeType) {
            case 'image/jpeg':
                imagejpeg($resizedImage);
                break;
            case 'image/png':
                imagepng($resizedImage);
                break;
            case 'image/gif':
                imagegif($resizedImage);
                break;
        }
        $resizedImageData = ob_get_clean();
        $base64Image = base64_encode($resizedImageData);

        return "data:$mimeType;base64,$base64Image";
    }
}