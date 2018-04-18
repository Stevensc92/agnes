<?php

namespace Agnes\Controller;

class AppController
{
    protected $twig;
    protected $router;

    public function __construct($router)
    {
        $this->router = $router;

        $loader = new \Twig_Loader_Filesystem('src/View/');

        // if we are in development, we don't need cache instead in production
        if(preg_match('#localhost#', $_SERVER['HTTP_HOST']))
        {
            $this->twig = new \Twig_Environment($loader, array(
                //'cache' => 'compilation_cache',
            ));
        }
        else
        {
            $this->twig = new \Twig_Environment($loader, array(
                'cache' => 'compilation_cache',
            ));
        }

        /**
         * Create a TWIG function
         * Return the path to the public folder who contains the css,js and img folder.
         * @param string param (need to be like 'path/to/folder/file.ext')
         * @return string
         */

        $asset = new \Twig_Function('asset', function($param): string {
            $publicDir = 'public/';
            return $publicDir.$param;
        });

        /**
         * Create a TWIG function
         * Return the path of a route
         * @param string routeName
         * @return string path
         */
        $path = new \Twig_Function('path', function($routeName, $params = array()): string{
            return str_replace('agnes2/', '.', $this->router->generate($routeName, $params));
        });

        $this->twig->addFunction($asset);
        $this->twig->addFunction($path);
    }

    public function notFound(): void
    {
        echo $this->twig->render('error/404.html.twig');
    }
}
