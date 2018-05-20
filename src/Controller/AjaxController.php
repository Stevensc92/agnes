<?php

namespace Agnes\Controller;

use Agnes\Model\CommentModel;

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
}
