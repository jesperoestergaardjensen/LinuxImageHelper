<?php

namespace LinuxImageHelper\Service;

use LinuxImageHelper\Model\Image;

class ImageService
{
    function resizeToHeight(Image $image, int $height) : Image
    {
        $ratio = $height / $image->getHeight();
        $width = $image->getWidth() * $ratio;
        return $this->resize($image, $width, $height);
    }

    function resizeToWidth(Image $image, int $width) : Image
    {
        $ratio = $width / $image->getWidth();
        $height = $image->getheight() * $ratio;
        return $this->resize($image, $width, $height);
    }

    function resize(Image $image, int $width, int $height) : Image
    {
        $new_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $image->getImageResource(), 0, 0, 0, 0, $width, $height, $image->getWidth(),
            $image->getHeight());

        $image_type_class_name = get_class($image);
        return new $image_type_class_name($image);
    }
}
