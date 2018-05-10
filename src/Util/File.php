<?php

namespace Agnes\Util;

class File
{
    public function __construct()
    {

    }

    public static function getExtension($filename)
    {
        $extension = explode('.', $filename);
        $extension = $extension[count($extension) - 1];
        return $extension;
    }
}
