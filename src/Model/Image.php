<?php

namespace LinuxImageHelper\Model;

abstract class Image
{
    // TODO : Use GdImage class as type when we are php 8.0 ready
    private $image_resource;

    public function __construct($jpg_file_resource)
    {
        $this->image_resource = $jpg_file_resource;
    }

    function getWidth() : int
    {
        return imagesx($this->image_resource);
    }

    function getHeight() : int
    {
        return imagesy($this->image_resource);
    }

    public function getImageResource()
    {
        return $this->image_resource;
    }
}