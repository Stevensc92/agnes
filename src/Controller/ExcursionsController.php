<?php

namespace Agnes\Controller;

use Agnes\Util\DBConnection;
use Agnes\Model\ExcursionsModel;

/**
 * Class ExcursionsController
 * @package Agnes\Controller
 * @Route('/admin/excursions')
 */
class ExcursionsController extends AppController
{
    /**
     * @Route("/", name="listExcursions")
     * @Method("GET")
     */
    public function list()
    {
        if (!$this->is_granted('ROLE_ADMIN'))
            $this->notFound();

        echo $this->twig->render('admin/excursions/list.html.twig', array(
            'excursions' => ExcursionsModel::findAll()
        ));
    }

    /**
     * @Route("/add", name="addExcursion")
     * @Method([GET|POST])
     */
    public function add()
    {
        if (!$this->is_granted('ROLE_ADMIN'))
            $this->notFound();

        if (isset($_POST['addExcursion'])) {
            $excursion = new ExcursionsModel();

            $error = [];
            if (empty($_POST['title'])) {
                $error['title'] = ['Le titre ne peut être vide'];
            }

            if (empty($_POST['description'])) {
                $error['description'] = ['La description ne peut être vide'];
            }

            if (empty($_POST['price'])) {
                $error['price'] = ['Le prix ne peut être vide'];
            }

//            if (!is_numeric())
        }

        echo $this->twig->render('admin/excursions/add.html.twig');
    }
}