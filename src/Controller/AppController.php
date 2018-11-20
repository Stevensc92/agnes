<?php

namespace Agnes\Controller;

use Agnes\Util\{FlashMessage, File, Slugify};

class AppController
{
    protected $twig;
    protected $router;
    protected $session;
    private $basePath;

    public function __construct(\AltoRouter $router, $basePath)
    {
        $this->basePath = $basePath;
        $this->router = $router;

        $loader = new \Twig_Loader_Filesystem('src/View/');

        // if we are in development, we don't need cache instead in production
        if(preg_match('#localhost#', $_SERVER['HTTP_HOST']))
        {
            $this->twig = new \Twig_Environment($loader, array(
                'cache' => false,
            ));
        }
        else
        {
            $this->twig = new \Twig_Environment($loader, array(
                'cache' => false,
            ));
        }

        $this->injectTwigFunction();

        @$this->session->flash = new FlashMessage();


        if (isset($_SESSION['user']))
        {
            $this->twig->addGlobal('app', [
                'user' => [
                    'username'  => $_SESSION['user']['username'],
                    'role'      => $_SESSION['user']['role'],
                ],
            ]);
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

    public function is_granted(string $role): bool
    {
        if (isset($_SESSION['user']) && isset($_SESSION['user']['role']))
        {
            if ($role === 'IS_AUTHENTICATED_FULLY')
                return true;
            else if ($role === $_SESSION['user']['role'])
                return true;
        }
        return false;
    }

    public function is_ajax()
    {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
            return true;
        return false;
    }

    public function injectTwigFunction()
    {
        /**
         * Create a TWIG function
         * Return the path to the public folder who contains the css,js and img folder.
         * @param string param (need to be like 'path/to/folder/file.ext')
         * @return string
         */

        $asset = new \Twig_Function('asset', function(string $param): string {
            if ($this->basePath != '')
                $publicDir = "/$this->basePath";
            else
                $publicDir = "";
            $publicDir .= "public/";
            return $publicDir.$param;
        });

        /**
         * Create a TWIG function
         * Return the path of a route
         * @param string routeName
         * @return string path
         */
        $path = new \Twig_Function('path', function(string $routeName, ...$params){
            if (isset($params) && count($params) > 0) {
                if (is_array($params[0])) {
                    $tmp = $params;
                    $params = $tmp[0];
                }
                $url = $this->router->generate($routeName, $params);
            } else {
                $url = $this->router->generate($routeName);
            }

            if ($this->basePath == 'agnes/') {
                $toSearch = $this->basePath.'/';
                $isSlasher = strpos($url, $toSearch);
                if ($isSlasher == 0)
                    $url = str_replace($toSearch, '/'.$this->basePath, $url);
            }

            return $url;
        });

        /**
         * Create a TWIG function
         * Return bool if user can access to a route
         * @param string role [role to match with the user role]
         * @return boolean
         */
        $is_granted = new \Twig_Function('is_granted', function(string $role){
            return $this->is_granted($role);
        });

        /**
         * Create a twig function
         * Dump a var passed from twig
         * @param array|string var
         * @return void
         */
        $dump = new \Twig_Function('dump', function($var) {
            return var_dump($var);
        });

        /**
         * Create a twig function
         * Slug a var passed from twig
         * @param array|string var
         * @return void
         */
        $slugify = new \Twig_Function('slugify', function(string $data) {
            return Slugify::slug($data);
        });

        $displayVar = new \Twig_Function('displayVar', function(string $data) {
            return htmlspecialchars($data);
        });

        $getExtension = new \Twig_Function('getExtension', function(string $filename) {
            return '.'.File::getExtension($filename);
        });


        /** Function php into twig **/
        $str_replace = new \Twig_Function('str_replace', function($search, $replace, $subject) {
            $data = str_replace($search, $replace, $subject);
            return $data;
        });

        $current = new \Twig_Function('current', function(&$arr) {
            return current($arr);
        });

        $substr = new \Twig_Function('substr', function($string, $start, $length = null) {
            if ($length != null && is_int($length))
                return substr($string, $start, $length);

            return substr($string, $start);
        });

        $end = new \Twig_Function('end', function(&$arr) {
            return end($arr);
        });

        $strtotime = new \Twig_Function('strtotime', function($time) {
            return strtotime($time);
        });

        $clone = new \Twig_Function('clone', function($var) {
            return clone($var);
        });

        $explode = new \Twig_Function('explode', function($delimiter, $string) {
            return explode($delimiter, $string);
        });

        $implode = new \Twig_Function('implode', function($glue, $arr) {
            return implode($glue, $arr);
        });

        $this->twig->addFunction($asset);
        $this->twig->addFunction($path);
        $this->twig->addFunction($is_granted);
        $this->twig->addFunction($dump);
        $this->twig->addFunction($slugify);
        $this->twig->addFunction($displayVar);
        $this->twig->addFunction($getExtension);
        $this->twig->addFunction($str_replace);
        $this->twig->addFunction($current);
        $this->twig->addFunction($substr);
        $this->twig->addFunction($end);
        $this->twig->addFunction($strtotime);
        $this->twig->addFunction($clone);
        $this->twig->addFunction($explode);
        $this->twig->addFunction($implode);
    }
}
