<?php

namespace Agnes\Controller;

use Agnes\Util\FlashMessage;

class AppController
{
    protected $twig;
    protected $router;
    protected $session;

    public function __construct(\AltoRouter $router)
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

        $asset = new \Twig_Function('asset', function(string $param): string {
            $publicDir = '/agnes2/public/';
            return $publicDir.$param;
        });

        /**
         * Create a TWIG function
         * Return the path of a route
         * @param string routeName
         * @return string path
         */
        $path = new \Twig_Function('path', function(string $routeName, $params = array()): string{
            return str_replace('agnes2/', '/agnes2', $this->router->generate($routeName, $params));
        });

        /**
         * Create a TWIG function
         * Return bool if user can access to a route
         * @param string role [role to match with the user role]
         * @return boolean
         */
        $is_granted = new \Twig_Function('is_granted', function(string $role){
            if ($role === $_SESSION['user']['role'])
                return true;
            return false;
        });

        $this->twig->addFunction($asset);
        $this->twig->addFunction($path);
        $this->twig->addFunction($is_granted);

        @$this->session->flash = new FlashMessage();


        if (isset($_SESSION['user']))
        {
            $this->twig->addGlobal('app', array(
                'username'  => $_SESSION['user']['username'],
                'role'      => $_SESSION['user']['role'],
            ));
        }

        if (isset($_SESSION['flashMessage']))
        {
            $this->twig->addGlobal('flashMessage', array(
                'content'   => $_SESSION['flashMessage']['message'],
                'type'      => $_SESSION['flashMessage']['type'],
            ));
            unset($_SESSION['flashMessage']);
        }
    }

    public function notFound(): void
    {
        echo $this->twig->render('error/404.html.twig');
    }
}
