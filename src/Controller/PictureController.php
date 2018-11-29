<?php
namespace Agnes\Controller;

use Agnes\Model\PictureModel;
use Agnes\Model\CategoryModel;
use Agnes\Model\CommentModel;

class PictureController extends AppController
{
    /**
     * @Route('/picture/[i:id]', name="showPicture")
     * @Method('GET')
     */
    public function showPicture(array $params)
    {
        if (!array_key_exists('id', $params)) {
            die($this->notFound());
        }

        $id = $params['id'];

        $picture = PictureModel::findOneById($id);
        $comments = CommentModel::getAllByPictureIdLimit($id, 0, 5);
        $nbComments = CommentModel::getAllByPictureId($id);

        echo $this->twig->render('picture/show.html.twig', array(
            'picture'       => $picture,
            'comments'      => $comments,
            'nbComments'    => count($nbComments),
        ));
    }

    /**
     * @Route('/picture/[a:category']', name="showCategory")
     * @Method('GET')
     */
    public function showCategory(array $params)
    {
        if (!array_key_exists('category', $params)) {
            die($this->notFound());
        }
        $categoryName = $params['category'];

        $category = CategoryModel::findOneByName($categoryName);
        $pictures = PictureModel::findByCategory($category->getId());
        echo $this->twig->render('category/show.html.twig', array(
            'pictures' => $pictures,
        ));
    }
}

?>
