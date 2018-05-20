<?php

namespace Agnes;

use Agnes\Config\Config;
use Agnes\Controller\AppController;
use Agnes\Util\DBConnection;
use Agnes\Util\Router;

class App
{
    private $router;

    public function __construct()
    {
        $this->router = new Router();

        $basePath = str_replace("\src", "", str_replace($_SERVER['DOCUMENT_ROOT'], "", __DIR__));

        $this->router->setBasePath('agnes2/');

        /**
         * Primary routes
         */
        $this->router->map('GET', '/', 'IndexController#index', 'index');
        $this->router->map('GET', '/contact', 'IndexController#contact', 'contact');


        /**
         * User routes
         */
        $this->router->map('POST',  '/login',   'UserController#login',     'login');
        $this->router->map('POST',  '/signup',  'UserController#signUp',    'signUp');
        $this->router->map('GET',   '/logout',  'UserController#logout',    'logout');

        /**
         * Back office routes
         */
        $this->router->map('GET', '/admin', 'BackOfficeController#index', 'backOfficeIndex');

            /**
             * Picture routes
             */
             $this->router->map('GET',  '/picture/[i:id]',          'PictureController#showPicture',        'showPicture');
             $this->router->map('GET',  '/picture/[a:category]',    'PictureController#showCategory',       'showCategory');
             $this->router->map('GET',  '/admin/picture/add',       'BackOfficeController#addPicture',      'addPicture');
             $this->router->map('POST', '/admin/picture/upload',    'BackOfficeController#uploadPicture',   'uploadPicture');
             $this->router->map('GET',  '/admin/picture',           'BackOfficeController#listPicture',     'listPicture');

        /**
         * Ajax routes
         */
        $this->router->map('POST', '/admin/picture/update', 'BackOfficeController#updatePicture',   'updatePicture');
        $this->router->map('POST', '/admin/picture/delete', 'BackOfficeController#deletePicture',   'deletePicture');
        $this->router->map('POST', '/ajax/getcomment',      'AjaxController#getComment',               'getComment');

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
