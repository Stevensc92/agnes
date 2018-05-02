<?php

namespace Agnes\Controller;

use Agnes\Util\DBConnection;
use Humps\FileUploader\FileUploader;
use Agnes\Model\UserModel;
use Agnes\Model\CategoryModel;
use Agnes\Model\PictureModel;


class BackOfficeController extends AppController
{
    /**
     * @Route('/administration', name="backOfficeIndex")
     * @Method('GET')
     */
    public function index(): void
    {
        $this->notFound();
    }

    /**
     * @Route('/picture/add', name="addPicture")
     * @Method('GET')
     * @ROLE_ADMIN required
     */
    public function addPicture()
    {
        if (isset($_SESSION['user']) && 'ROLE_ADMIN' == $_SESSION['user']['role'])
        {
            $categories = CategoryModel::findAll();
            echo $this->twig->render('picture/add.html.twig', array(
                'categories' => $categories,
            ));
        }
        else
        {
            $this->notFound();
        }
    }

    /**
     * @Route('/picture/upload', name="uploadPicture")
     * @Method('POST')
     */
    public function uploadPicture()
    {
        $errorMessage = '';
        if (!isset($_FILES['files']) || count($_FILES['files']['name']) < 1)
            $errorMessage .= 'Aucune image sélectionnée à uploader.';
        if (!isset($_POST['category']) || $_POST['category'] == 'none')
            $errorMessage .= 'Aucune catégorie sélectionnée.';

        if ($errorMessage != '')
        {
            $this->session->flash->setFlashMessage($errorMessage, 'error');
            $this->router->redirect('/agnes2/picture/add');
        }

        $success;
        $fail;

        for ($i = 0; $i < count($_FILES['files']['name']); $i ++)
        {
            $file = [
                'name' => $_FILES['files']['name'][$i],
                'type' => $_FILES['files']['type'][$i],
                'tmp_name' => $_FILES['files']['tmp_name'][$i],
                'error' => $_FILES['files']['error'][$i],
                'size' => $_FILES['files']['size'][$i],
            ];

            $db = DBConnection::getInstance();

            $picture = new PictureModel();
            $category = new CategoryModel();
            $category = $category->findById($_POST['category']);

            $picture->setCategoryId($category);
            $picture->setFile($file['name']);
            $picture->insert();

            // si la photo est ajouté dans la bdd on l'upload par la suite
            if ($db->lastInsertId() > 0)
            {
                $file = \Humps\FileUploader\File::getUploadedFile($file);

                $uploader = new FileUploader($file);
                $uploader->allowedMimeTypes([
                    'image/jpeg',
                    'image/png',
                    'image/gif',
                ]);
                $uploader->uploadDir('public/uploads');

                // si la photo est bien uploadé, on incrémente une variable de success
                if ($uploader->upload())
                    $success++;
                else
                {
                    // sinon on supprime la photo de la base de donnée
                    $picture->deleteById($pictureLastInsertId);
                    // et on incrémente une variable de fail
                    $fail++;
                }
            }
            else // si la photo n'est pas ajouté dans la bdd, on incrémente la variable de fail
                $fail++;
        }

        // On structure le message d'echec/ de réussite au maximum
        if ($success > 0)
        {
            $success_S = ($success) > 1 ? 's' : '';
            $message = "$success image$success_S uploadée$success_S";
        }

        if ($fail > 0)
        {
            $message .= " et $fail échec d'upload";
            $this->session->flash->setFlashMessage($message, 'error');
        }

        $this->session->flash->setFlashMessage($message, 'success');
        $this->router->redirect('/agnes2/picture/add');
    }
}
