<?php

namespace Agnes\Controller;

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

            $comment = new CommentModel();
            $comment->setIdUser($user)
            ->setContent($content)
            ->setIdPicture($picture)
            ->setCreatedAt(date('Y-m-d H:i:s', time()))
            ->insert();

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
}
