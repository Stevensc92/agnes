<?php

namespace Agnes\Util;

class Router extends \AltoRouter
{
    public function redirect(string $path): void
    {
        header('Location: '.$path);
    }

    public function redirectToRoute($routeName, $params = array()): void
    {
        $base = '/';

        $url = $base.str_replace('agnes//', 'agnes/', $this->generate($routeName, $params));

        header('Location: '.$url);
    }
}
