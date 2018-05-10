<?php

namespace Agnes\Controller;

use Agnes\Model\CategoryModel;
use Agnes\Model\PictureModel;

class IndexController extends AppController
{
    /**
     * @Route('/', name="index")
     */
    public function index(): void
    {
        $pictures = new PictureModel;

        echo $this->twig->render('index/index.html.twig', [
            'categories'    => CategoryModel::findAll(),
            'pictures'      => PictureModel::findAllWithCategory(),
        ]);
    }


}
