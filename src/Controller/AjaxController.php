<?php

namespace Agnes\Controller;

use Agnes\Model\EventsModel;
use Agnes\Util\DBConnection;
use Agnes\Model\CommentModel;
use Agnes\Model\PictureModel;
use Agnes\Model\UserModel;

class AjaxController extends AppController
{
    public function getComment()
    {
        if ($this->is_ajax()) {
            $id_picture = $_POST['id_picture'];
            $commentPerPage = $_POST['commentPerPage'];
            $first_com = $_POST['first_com'];
            $comments = CommentModel::getAllByPictureIdLimit($id_picture, $first_com, $commentPerPage);

            $data = [];
            foreach ($comments as $comment)
            {
                $data[] = [
                    'com_username' => $comment->username,
                    'com_createdAt' => $comment->getCreatedAt(),
                    'com_content' => $comment->getContent(),
                ];
            }

            echo (json_encode($data));
        } else {
            return false;
        }
    }

    public function addComment()
    {
        if ($this->is_ajax()) {
            $db = DBConnection::getInstance();

            $picture = PictureModel::findById($_POST['id_picture']);
            $user = UserModel::findByUsername($_POST['username']);

            $content = $_POST['com_content'];


            $now = new \DateTime();
            $comment = new CommentModel();
            $comment->setIdUser($user)
            ->setContent($content)
            ->setIdPicture($picture)
            ->setCreatedAt($now)
            ->setIsActive(1)
            ->add();

            if ($db->lastInsertId() > 0) {
                $comments = CommentModel::getAllByPictureIdLimit($picture->getId(), 0, 5);

                $data = [];
                foreach ($comments as $comment) {
                    $data[] = [
                        'com_username' => $comment->username,
                        'com_createdAt' => $comment->getCreatedAt(),
                        'com_content' => $comment->getContent(),
                    ];
                }

                echo (json_encode($data));
            }
        } else {
            return false;
        }
    }

    public function putReservation()
    {
        if ($this->is_ajax()) {
            $errors = 0;
            $error = [];
            if (isset($_POST['data'])) {
                $data = $_POST['data'];
                foreach ($data as $field) {
                    switch ($field['name']) {
                        case "title":
                            if ($field['value'] == '') {
                                $error[] = ['input' => 'title', 'message' => 'Le titre ne peut être vide'];
                                $errors++;
                            }
                            if (strlen($field['value']) > 64) {
                                $error[] = ['input' => 'title', 'message' => 'Le titre ne peut pas être plus grand que 64 caractères'];
                                $errors++;
                            }
                            if (strlen($field['value']) < 8 && $field['value'] != '') {
                                $error[] = ['input' => 'title', 'message' => 'Le titre ne peut pas être plus petit que 8 caractères'];
                                $errors++;
                            }

                            $title = $field['value'];
                            break;

                        case "description":
                            if ($field['value'] == '') {
                                $error[] = ['input' => 'description', 'message' => 'La description ne peut être vide'];
                                $errors++;
                            }

                            $description = $field['value'];
                            break;

                        case "day":
                            if ($field['value'] == '') {
                                $error[] = ['input' => 'day', 'message' => 'Aucune date a été sélectionnée'];
                                $errors++;
                            }

                            $date = $field['value'];
                            break;
                    }
                }

                if ($errors == 0) {
                    $db = DBConnection::getInstance();
                    $event = new EventsModel();
                    $user = UserModel::findByUsername($_SESSION['user']['username']);

                    $event->setIdUser($user->getId())
                        ->setTitle($title)
                        ->setDescription($description)
                        ->setStart($date.' 00:00:00')
                        ->setEnd($date.' 23:00:00');

                    if ($event->add())
                        echo json_encode(['success' => ['success' => 'La demande a bien été enregistrée.']]);
                    else
                        echo json_encode(['success' => ['error' => 'La demande n\'a pas été enregistrée.']]);
                } else {
                    echo json_encode(['error' => $error]);
                }
            }
        }
    }
}
