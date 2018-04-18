<?php

namespace Agnes\Controller;

class UserController extends AppController
{

    /**
     * @Route('/login', name='user_login')
     */
    public function login(): void
    {
        echo $this->twig->render('user/login.html.twig');
    }
}

?>
