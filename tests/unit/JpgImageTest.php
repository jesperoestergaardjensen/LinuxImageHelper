<?php

namespace LinuxImageHelper\Tests\unit;

use LinuxImageHelper\Model\JpgImage;
use PHPUnit\Framework\TestCase;

class JpgImageTest extends TestCase
{
    private const JPG_SAMPLE_FILE_NAME   = 'sample.jpg';
    private const JPG_IMAGE_SAMPLE_WIDTH = 689;
    private const JPG_IMAGE_SAMPLE_HEIGHT = 689;

    private function getTestDataFolder(): string
    {
        return dirname(__DIR__) . "/data/";
    }

    public function testCreationWidthAndHeight()
    {
        $image_resource = @imagecreatefromjpeg($this->getTestDataFolder() . self::JPG_SAMPLE_FILE_NAME);

        $jpg_image = new JpgImage($image_resource);

        $this->assertEquals($image_resource, $jpg_image->getImageResource(), 'Image resource can be retrived correct again after construction');

        $this->assertEquals(self::JPG_IMAGE_SAMPLE_WIDTH, $jpg_image->getWidth(), 'sample image width should be ' . self::JPG_IMAGE_SAMPLE_WIDTH);
        $this->assertEquals(self::JPG_IMAGE_SAMPLE_HEIGHT, $jpg_image->getHeight(), 'sample image height should be ' . self::JPG_IMAGE_SAMPLE_HEIGHT);
    }
}
