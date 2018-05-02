<?php

namespace Agnes\Util;

class Router extends \AltoRouter
{
    public function redirect(string $path): void
    {
        header('Location: '.$path);
    }
}
