<?php

namespace Agnes\Controller;

use Agnes\Model\CategoryModel;
use Agnes\Model\PictureModel;

class IndexController extends AppController
{
    /**
     * @Route('/', name="index")
     */
    public function index()
    {
        $pictures = new PictureModel;

        echo $this->twig->render('index/index.html.twig', [
            'categories'    => CategoryModel::findAll(),
            'pictures'      => PictureModel::findAllWithCategory(),
        ]);
    }

    public function contact()
    {
        echo $this->twig->render('index/contact.html.twig');
    }
}
