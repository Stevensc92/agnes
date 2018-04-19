<?php

namespace Agnes;

use Agnes\Config\Config;
use Agnes\Controller\AppController;
use Agnes\Util\DBConnection;

class App
{
    private $router;

    public function __construct()
    {
        $this->router = new \AltoRouter();

        $basePath = str_replace("\src", "", str_replace($_SERVER['DOCUMENT_ROOT'], "", __DIR__));

        $this->router->setBasePath('agnes2/');

        /**
         * Primary routes
         */
        $this->router->map('GET', '/', 'IndexController#index', 'index');


        /**
         * User routes
         */
        $this->router->map('POST',  '/login',   'UserController#login',     'login');
        $this->router->map('POST',  '/signup',  'UserController#signUp',    'signUp');
        $this->router->map('GET',   '/logout', 'UserController#logout',    'logout');

        DBConnection::setConfig(Config::getConfig());
    }

    public function run()
    {
        $match = $this->router->match();

        if ($match)
        {
            $target = explode('#', $match['target']);

            $controllerName = "\\Agnes\Controller\\" . $target[0];
            $methodName = $target[1];

            $controller = new $controllerName($this->router);

            call_user_func([$controller, $methodName], $match['params']);
        }
        else
        {
            $controller = new AppController($this->router);

            $controller->notFound();
        }
    }
}
