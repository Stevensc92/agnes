<?php
namespace Agnes\Controller;

class PictureController extends AppController
{
    /**
     * @Route('/picture/upload', name="upload_picture")
     * @Method('POST')
     */
    public function upload()
    {
        var_dump($_POST);
        // $this->
    }
}

?>
