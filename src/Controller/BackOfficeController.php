<?php

namespace Agnes\Controller;

use Agnes\Util\DBConnection;
use Agnes\Util\File;
use Agnes\Util\Slugify;
use Agnes\Model\UserModel;
use Agnes\Model\EventsModel;
use Agnes\Model\CategoryModel;
use Agnes\Model\PictureModel;
use Humps\FileUploader\FileUploader;


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

        echo $this->twig->render('admin/index.html.twig');
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
            $this->router->redirectToRoute('addPicture');
        }

        $success = 0;
        $fail = 0;

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
                    $picture->deleteById($db->lastInsertId());
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
        $this->router->redirectToRoute('addPicture');
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
        if (is_ajax())
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

                        $link = '../agnes/public/uploads/';
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

    /**
     * @Route("/admin/picture/delete", name="deletePicture")
     * @Method("POST")
     */
    public function deletePicture()
    {
        if (is_ajax())
        {
            $response = [];
            $id = $_POST['id'];
            $picture = PictureModel::findById($id);

            if (PictureModel::deleteById($id) && unlink('../agnes2/public/uploads/'.$picture->getFilename().$picture->getExtension()))
                $response = array('success' => 'success');
            else
                $response = array('fail' => 'fail');

            echo json_encode($response);
        }
    }

    /****************************
     *                          *
     *                          *
     *                          *
     *                          *
     * Routes for Events        *
     *                          *
     *                          *
     *                          *
     *                          *
     ****************************/

    /**
     * @Route("/admin/events/", name="listEvents")
     * @Method("GET")
     */
    public function listEvents() {
        if (!$this->is_granted('ROLE_ADMIN'))
        {
            $this->notFound();
            return false;
        }

        $events = EventsModel::findAll('id DESC');

        echo $this->twig->render('admin/events/list.html.twig', array(
            'events' => $events
        ));
    }

    public function addEvent() {
        if (!$this->is_granted('ROLE_ADMIN')) {
            $this->notFound();
            return false;
        }

        if (isset($_POST['addEvent'])) {
            $event = new EventsModel();

            if (!empty($_POST['id_user'])) {
                $event->setIdUser($_POST['id_user']);
            }
            $event->setTitle($_POST['title'])
                    ->setDescription($_POST['description'])
                    ->setStart($_POST['start'].' 00:00:00')
                    ->setEnd($_POST['end'].' 23:00:00')
                    ->setIsActive(1);

            if ($event->add()) {
                $this->session->flash->setFlashMessage('L\évènement a bien été créé', 'success');
            } else {
                $this->session->flash->setFlashMessage('L\'évènement n\'a pas été créé', 'warning');
            }

            $this->router->redirectToRoute('listEvents');
        }

        echo $this->twig->render('admin/events/add.html.twig');
    }

    /**
     * @Route("/admin/events/update/[i:id]", name="editEvent")
     * @Method("GET|POST")
     */
    public function editEvent($param) {
        if (!$this->is_granted('ROLE_ADMIN'))
        {
            $this->notFound();
            return false;
        }

        $event = EventsModel::findById($param['id']);

        $start = new \DateTime($event->getStart());
        $start = $start->format('Y-m-d');

        $end = new \DateTime($event->getEnd());
        $end = $end->format('Y-m-d');

        $user = UserModel::findById($event->getIdUser());

        $eventArray = [
            'id' => $event->getId(),
            'user' => ['id' => $user->getId(), 'username' => $user->getUsername()],
            'title' => $event->getTitle(),
            'description' => $event->getDescription(),
            'start' => $start,
            'end' => $end,
            'isActive' => $event->getIsActive(),
        ];

        if (isset($_POST['editEvent'])) {

            $data = [];
            foreach ($_POST as $key => $value) {
                if ($key != 'editEvent' && $key != 'id')
                    $data[$key] = $value;
            }

            $item = new EventsModel();
            if ($item->update($data, $_POST['id'])) {
                $this->session->flash->setFlashMessage('L\évènement à bien été modifié', 'success');
            } else {
                $this->session->flash->setFlashMessage('L\'évènement n\'a pas été modifié', 'warning');
            }
            $this->router->redirectToRoute('listEvents');
        }

        echo $this->twig->render('admin/events/edit.html.twig', array(
            'event' => $eventArray
        ));
    }

    /**
     * @Route("/admin/events/delete/[i:id]", name="deleteEvent")
     * @Method("POST")
     */
    public function deleteEvent($param) {
        if (!$this->is_granted('ROLE_ADMIN'))
        {
            $this->notFound();
            return false;
        }

        if (EventsModel::deleteById($param['id'])) {
            $this->session->flash->setFlashMessage('L\'évènement a bien été supprimé.', 'success');
        } else {
            $this->session->flash->setFlashMessage('L\'évènement n\'a été supprimé.');
        }

        $this->router->redirectToRoute('listEvents');
    }
}
