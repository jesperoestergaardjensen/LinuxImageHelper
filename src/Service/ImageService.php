<?php

namespace LinuxImageHelper\Service;

use LinuxImageHelper\Exception\LinuxImageHelperException;
use LinuxImageHelper\Model\Image;

abstract class ImageService
{
    function resizeToHeight(Image $image, int $height): Image
    {
        $ratio = $height / $image->getHeight();
        $width = $image->getWidth() * $ratio;

        return $this->resize($image, $width, $height);
    }

    function resizeToWidth(Image $image, int $width): Image
    {
        $ratio = $width / $image->getWidth();
        $height = $image->getheight() * $ratio;

        return $this->resize($image, $width, $height);
    }

    /**
     * @throws LinuxImageHelperException
     */
    function resize(Image $image, int $width, int $height): Image
    {
        $new_image = imagecreatetruecolor($width, $height);
        $resize_success = imagecopyresampled(
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

        if ($resize_success === false) {
            throw new LinuxImageHelperException('Resize image failed');
        }

        $image_type_class_name = get_class($image);

        return new $image_type_class_name($new_image);
    }
}
