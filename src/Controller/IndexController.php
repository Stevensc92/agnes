<?php

namespace Agnes\Controller;

class IndexController extends AppController
{
    /**
     * @Route('/', name="index")
     */
    public function index(): void
    {
        echo $this->twig->render('index/index.html.twig', array('name' => 'Stevens'));
    }


}
