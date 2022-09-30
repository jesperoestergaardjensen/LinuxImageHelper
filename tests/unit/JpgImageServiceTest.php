<?php

namespace LinuxImageHelper\Tests\unit;

use LinuxFileSystemHelper\Tests\unit\FolderHelperTest;
use LinuxImageHelper\Exception\LinuxImageHelperException;
use LinuxImageHelper\Model\JpgImage;
use LinuxImageHelper\Service\JpgImageService;
use PHPUnit\Framework\TestCase;

class JpgImageServiceTest extends TestCase
{
    private const JPG_SAMPLE_FILE_NAME         = 'sample.jpg';
    private const JPG_SAMPLE_2_FILE_NAME       = 'sample2.jpg';
    private const JPG_SAMPLE_RESIZED_FILE_NAME = 'resized_sample.jpg';
    private const NOT_A_JPEG_FILE_NAME         = 'sample-green-200x200.png';
    private const NOT_AN_IMAGE_FILE            = 'not-a-image-file.jpg';
    private const RESIZE_FAIL_IMAGE_FILE       = 'resize-fail.jpg';

    public static function tearDownAfterClass(): void
    {
        unlink(self::getTestDataFolder() . self::JPG_SAMPLE_RESIZED_FILE_NAME);
    }

    private static function getTestDataFolder(): string
    {
        return dirname(__DIR__) . "/data/";
    }

    public function testCreateJpg()
    {
        $jpg_image_service = new JpgImageService();
        $jpg_image = $jpg_image_service->createJpg($this->getTestDataFolder() . self::JPG_SAMPLE_FILE_NAME);

        $this->assertTrue($jpg_image instanceof JpgImage, 'model created should be jpg image');
    }

    public function testResize()
    {
        $jpg_image_service = new JpgImageService();
        $jpg_image = $jpg_image_service->createJpg($this->getTestDataFolder() . self::JPG_SAMPLE_2_FILE_NAME);
        $resized_image = $jpg_image_service->resize($jpg_image, 100, 200);

        $this->assertEquals(100, $resized_image->getWidth(), 'jpg width should be resized to 100 px');
        $this->assertEquals(200, $resized_image->getHeight(), 'jpg height should be resized to 200 px');
    }

    public function testFailingResize()
    {
        $jpg_image_service = new JpgImageService();
        $jpg_image = $jpg_image_service->createJpg($this->getTestDataFolder() . self::RESIZE_FAIL_IMAGE_FILE);
        $this->expectException(LinuxImageHelperException::class);
        $this->expectExceptionMessage('Resize image failed');
        $jpg_image_service->resize($jpg_image, 0, 0);
    }

    public function testResizeToWidth()
    {
        $jpg_image_service = new JpgImageService();
        $jpg_image = $jpg_image_service->createJpg($this->getTestDataFolder() . self::JPG_SAMPLE_2_FILE_NAME);
        $resized_image = $jpg_image_service->resizeToWidth($jpg_image, 100);

        $this->assertEquals(100, $resized_image->getWidth(), 'jpg width should be resized to 100 px');
    }

    public function testResizeToHeight()
    {
        $jpg_image_service = new JpgImageService();
        $jpg_image = $jpg_image_service->createJpg($this->getTestDataFolder() . self::JPG_SAMPLE_2_FILE_NAME);
        $resized_image = $jpg_image_service->resizeToHeight($jpg_image, 120);

        $this->assertEquals(120, $resized_image->getHeight(), 'jpg width should be resized to 120 px');
    }

    public function testSaveToDisc()
    {
        $jpg_image_service = new JpgImageService();
        $jpg_image = $jpg_image_service->createJpg($this->getTestDataFolder() . self::JPG_SAMPLE_FILE_NAME);
        $resized_image = $jpg_image_service->resize($jpg_image, 177, 177);
        $jpg_image_service->saveJpgToDisc($resized_image,
            $this->getTestDataFolder() . self::JPG_SAMPLE_RESIZED_FILE_NAME);

        $this->assertTrue(file_exists($this->getTestDataFolder() . self::JPG_SAMPLE_RESIZED_FILE_NAME),
            'a new jpg file was created');
    }

    public function testcreateFromNonJpg()
    {
        $jpg_image_service = new JpgImageService();
        $this->expectException(LinuxImageHelperException::class);
        $jpg_image_service->createJpg($this->getTestDataFolder() . self::NOT_AN_IMAGE_FILE);
    }

    public function testcreateFromWrongFileType()
    {
        $jpg_image_service = new JpgImageService();

        $this->expectException(LinuxImageHelperException::class);
        $this->expectExceptionMessage('Use this service only to create jpg files, supplied mine type was image/png');

        $jpg_image_service->createJpg($this->getTestDataFolder() . self::NOT_A_JPEG_FILE_NAME);
    }
}
