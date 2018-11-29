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

            $errorMessage = "";
            if (empty($_POST['title'])) {
                $errorMessage .= 'Le titre ne peut être vide.<br>';
            }

            if (empty($_POST['description'])) {
                $errorMessage .= 'La description ne peut être vide.<br>';
            }

            if (empty($_POST['price'])) {
                $errorMessage .= 'Le prix ne peut être vide.<br>';
            }

            if (!preg_match('/[0-9]+/', $_POST['price'])) {
                $errorMessage .= 'Le prix doit obligatoirement être un entier.<br>';
            }

            if (!empty($errorMessage)) {
                $this->session->flash->setFlashMessage($errorMessage, 'error');
                $this->router->redirectToRoute('addExcursion');
            }



            $db = DBConnection::getInstance();

            $excursion->setTitle($_POST['title'])
                      ->setDescription($_POST['description'])
                      ->setPrice($_POST['price'])
                      ->add();

            if ($db->lastInsertId() > 0) {
                $this->session->flash->setFlashMessage("L'excursions a bien été ajoutée.", "success");
                $this->router->redirectToRoute('listExcursions');
            }

        }

        echo $this->twig->render('admin/excursions/add.html.twig');
    }

    public function edit($param)
    {
        if (!$this->is_granted('ROLE_ADMIN')) {
            $this->notFound();
            return false;
        }

        $excursion = ExcursionsModel::findById($param['id']);

        $excursion = [
            'id' => $excursion->getId(),
            'title' => $excursion->getTitle(),
            'price' => $excursion->getPrice(),
            'description' => $excursion->getDescription(),
        ];

        if (isset($_POST['editExcursion'])) {

            $data = [];
            foreach ($_POST as $key => $value) {
                if ($key != 'editExcursion' && $key != 'id')
                    $data[$key] = $value;
            }

            $item = new ExcursionsModel();
            if ($item->update($data, $_POST['id'])) {
                $this->session->flash->setFlashMessage('L\'excursion a bien été modifiée', 'success');
            } else {
                $this->session->flash->setFlashMessage('L\'excursion n\'a pas été modifiée', 'warning');
            }
            $this->router->redirectToRoute('listExcursions');
        }

        echo $this->twig->render('admin/excursions/edit.html.twig', array(
            'excursion' => $excursion
        ));
    }

    public function delete($param)
    {
        if (!$this->is_granted('ROLE_ADMIN'))
        {
            $this->notFound();
            return false;
        }

        if (ExcursionsModel::deleteById($param['id'])) {
            $this->session->flash->setFlashMessage('L\'excursion a bien été supprimée.', 'success');
        } else {
            $this->session->flash->setFlashMessage('L\'excursion n\'a pas été supprimée.');
        }

        $this->router->redirectToRoute('listExcursions');
    }
}