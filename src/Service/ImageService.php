<?php

namespace LinuxImageHelper\Service;

use LinuxImageHelper\Exception\LinuxImageHelperException;
use LinuxImageHelper\Model\Image;
use Throwable;

abstract class ImageService
{
    public function resizeToHeight(Image $image, int $height): Image
    {
        $ratio = $height / $image->getHeight();
        $width = $image->getWidth() * $ratio;

        return $this->resize($image, $width, $height);
    }

    public function resizeToWidth(Image $image, int $width): Image
    {
        $ratio = $width / $image->getWidth();
        $height = $image->getheight() * $ratio;

        return $this->resize($image, $width, $height);
    }

    /**
     * @throws LinuxImageHelperException
     */
    public function resize(Image $image, int $width, int $height): Image
    {
        try {
            $new_image = imagecreatetruecolor($width, $height);
            imagecopyresampled(
                $new_image,
                $image->getImageResource(),
                0,
                0,
                0,
                0,
                $width,
                $height,
                $image->getWidth(),
                $image->getHeight()
            );
        } catch (Throwable $exception) {
            throw new LinuxImageHelperException('Resize image failed');
        }

        $image_type_class_name = get_class($image);

        return new $image_type_class_name($new_image);
    }
}
