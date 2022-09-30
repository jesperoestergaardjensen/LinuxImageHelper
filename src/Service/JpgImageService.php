<?php

namespace LinuxImageHelper\Service;

use LinuxImageHelper\Exception\LinuxImageHelperException;
use LinuxImageHelper\Model\JpgImage;
use Throwable;

class JpgImageService extends ImageService
{
    /**
     * @param string $filename_and_path
     *
     * @return JpgImage
     * @throws LinuxImageHelperException
     */
    public function createJpg(string $filename_and_path): JpgImage
    {
        // Check for the required GD extension
        if(extension_loaded('gd')) {
            // Ignore JPEG warnings that cause imagecreatefromjpeg() to fail
            ini_set('gd.jpeg_ignore_warning', 1);
        } else {
            throw new LinuxImageHelperException('Required extension GD is not loaded.');
        }

        try {
            $image_info = getimagesize($filename_and_path);
        } catch (Throwable $exception) {
            throw new LinuxImageHelperException($exception->getMessage());
        }

        $image_type = $image_info[2];

        if ($image_type !== IMAGETYPE_JPEG) {
            throw new LinuxImageHelperException('Use this service only to create jpg files, supplied mine type was ' . image_type_to_mime_type($image_type));
        }

        if (! function_exists('imagecreatefromjpeg')) {
            throw new LinuxImageHelperException(['imglib_unsupported_imagecreate', 'imglib_jpg_not_supported']);
        }

        $image_resource = @imagecreatefromjpeg($filename_and_path);

        return new JpgImage($image_resource);
    }

    function saveJpgToDisc(JpgImage $jpg_image, string $filename_and_path, int $compression = 90): void
    {
        imageinterlace($jpg_image->getImageResource(), 1);
        imagejpeg($jpg_image->getImageResource(), $filename_and_path, $compression);
    }
}
