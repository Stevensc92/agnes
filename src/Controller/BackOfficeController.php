<?php

namespace Agnes\Controller;

use Agnes\Util\DBConnection;
use Agnes\Util\File;
use Agnes\Util\Slugify;
use Humps\FileUploader\FileUploader;
use Agnes\Model\UserModel;
use Agnes\Model\CategoryModel;
use Agnes\Model\PictureModel;


class BackOfficeController extends AppController
{
    /**
     * @Route('/admin', name="backOfficeIndex")
     * @Method('GET')
     */
    public function index(): void
    {
        if (!$this->is_granted('ROLE_ADMIN'))
            $this->notFound();
    }

    /**
     * @Route('/admin/picture/add', name="addPicture")
     * @Method('GET')
     * @ROLE_ADMIN required
     */
    public function addPicture()
    {
        if (!$this->is_granted('ROLE_ADMIN'))
            $this->notFound();

        $categories = CategoryModel::findAll();
        echo $this->twig->render('picture/add.html.twig', array(
            'categories' => $categories,
        ));
    }

    /**
     * @Route('/admin/picture/upload', name="uploadPicture")
     * @Method('POST')
     */
    public function uploadPicture()
    {
        if (!$this->is_granted('ROLE_ADMIN'))
            $this->notFound();

        $errorMessage = '';
        if (!isset($_FILES['files']) || count($_FILES['files']['name']) < 1)
            $errorMessage .= 'Aucune image sélectionnée à uploader.';
        if (!isset($_POST['category']) || $_POST['category'] == 'none')
            $errorMessage .= 'Aucune catégorie sélectionnée.';

        if ($errorMessage != '')
        {
            $this->session->flash->setFlashMessage($errorMessage, 'error');
            $this->router->redirect('/agnes2/admin/picture/add');
        }

        $success;
        $fail;

        for ($i = 0; $i < count($_FILES['files']['name']); $i++)
        {
            $file = [
                'name' => $_FILES['files']['name'][$i],
                'type' => $_FILES['files']['type'][$i],
                'tmp_name' => $_FILES['files']['tmp_name'][$i],
                'error' => $_FILES['files']['error'][$i],
                'size' => $_FILES['files']['size'][$i],
            ];

            $db = DBConnection::getInstance();

            $category = new CategoryModel();
            $category = $category->findById($_POST['category']);

            $picture = new PictureModel();

            $extension = '.'.File::getExtension($file['name']);
            $filename = Slugify::slug(str_replace($extension, '', $file['name']));
            $file['name'] = $filename.$extension;
            list ($width, $height) = getimagesize($file['tmp_name']);

            $picture->setIdCategory($category)
            ->setFilename($filename)
            ->setExtension($extension)
            ->setWidth($width)
            ->setHeight($height)
            ->insert();

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
        $this->router->redirect('/agnes2/admin/picture/add');
    }

    /**
     * @Route("/admin/picture/delete", name="deletePicture")
     * @Method("GET")
     */
    public function deletePicture()
    {
        if (!$this->is_granted('ROLE_ADMIN'))
            $this->notFound();

        echo $this->twig->render('picture/delete.html.twig');
    }

    /**
     * @Route("/admin/picture", name="listPicture")
     * @Method("GET")
     */
    public function listPicture()
    {
        if (!$this->is_granted('ROLE_ADMIN'))
        {
            $this->notFound();
            return false;
        }

        $pictures = [];
        // foreach(PictureModel::findAll() as $picture){
        //     $extension = '.'.File::getExtension($picture->getFile());
        //     $filename = str_replace($extension, '', $picture->getFile());
        //     $picture->setFile($filename);
        //     $picture->setExtension($extension);
        //
        //     $pictures[] = [
        //         $picture
        //     ];
        // }

        echo $this->twig->render('picture/list.html.twig', [
            'pictures' => PictureModel::findAll(),
        ]);
    }

    /**
     * Ajax Routes
     * @Route("/admin/picture/update", name="updatePicture")
     * @Method("POST")
     */
    public function updatePicture()
    {
        // if (!is_granted('ROLE_ADMIN'))
        // {
        //     $this->notFount();
        //     return false;
        // }
        if ($this->is_ajax())
        {

            $data = [];
            $id = $_POST['id'];

            if (empty($_POST['data']))
                return false;

            $picture = PictureModel::findById($id);

            foreach ($_POST['data'] as $column)
            {
                // print_r($column);
                if (isset($column[2]))
                {
                    // S'il y a le nom original du fichier dans les données
                    // c'est qu'on veut modifier le nom du fichier

                    $originFilename = $column[2];

                    if ($originFilename != $column[1])
                    {
                        $newName = Slugify::slug($column[1]);
                        $renamed = false;

                        $link = '../agnes2/public/uploads/';
                        // Si le nom du fichier a bien été modifié
                        if (rename($link.$originFilename.$picture->getExtension(), $link.$newName.$picture->getExtension()))
                        {
                            $picture->setFilename($newName);
                            $renamed = true;
                        }
                        else
                            return false;
                    }
                    $data[$column[0]] = Slugify::slug($column[1]);
                }
                else
                    $data[$column[0]] = $column[1];
            }

            if ($picture->update($data, $id))
                $data['id'] = $id;
            else
            {
                if ($renamed = true)
                {
                    if (rename($link.$newName.$picture->getExtension(), $link.$originFilename.$picture->getExtension()))
                        $data['fail'] = array('Update échoué');
                    else
                        $data['fail'] = array('Update échoué', 'Le nom du fichier à quand meme été modifié');
                    $data['id'] = $id;
                }
            }

            echo json_encode($data);
        }
    }
}
