<?php

namespace Agnes\Controller;

use Agnes\Model\UserModel;

class UserController extends AppController
{

    /**
     * @Route('/login', name='user_login')
     * @Method('post')
     */
    public function login(): void
    {
        if (isset($_POST['login']))
        {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = UserModel::findByUsername($username);

            // If we have an occurence = username exist on database
            if ($user)
            {
                $passwordIsValid = password_verify($password, $user->getPassword());
                if ($passwordIsValid && $user->getIsActive() == 1)
                {
                    $_SESSION['user']['username']   = $user->getUsername();
                    $_SESSION['user']['isActive']   = $user->getIsActive();
                    $_SESSION['user']['role']       = $user->getRole();


                    $flash['content']   = 'Connexion réussie';
                    $flash['type']      = 'success';

                    $this->session->flash->setFlashMessage($flash['content'], $flash['type']);
                    $this->router->redirect('./');
                }
                else
                {
                    $flash['content'] = 'Identifiant de connexion incorrect';
                    $flash['type'] = 'error';
                }
            }
            else
            {
                $flash['content'] = 'Identifiant de connexion incorrect';
                $flash['type'] = 'error';
            }

            echo $this->twig->render('index/index.html.twig', array(
                'flashMessage' => array(
                    'content'   => $flash['content'],
                    'type'      => $flash['type'],
                ),
                'lastUsername' => $username,
            ));
        }
    }

    /**
     * @Route('/signup', name="signUp")
     */
     public function signUp()
     {
         if (isset($_POST['signUp']))
         {
             $username = $_POST['signUpUsername'];
             $hash = password_hash($_POST['signUpPassword'], PASSWORD_DEFAULT);
             $email = $_POST['signUpEmail'];

             $user = new UserModel();
             $user->setUsername($username);
             $user->setPassword($hash);
             $user->setEmail($email);
             $user->setIsActive(1);
             $user->setRole('ROLE_USER');

             if ($user->add())
             {
                 $_SESSION['user']['username']   = $user->getUsername();
                 $_SESSION['user']['isActive']   = $user->getIsActive();
                 $_SESSION['user']['role']       = $user->getRole();

                 $this->session->flash->setFlashMessage('Inscription réussie', 'success');
                 $this->router->redirect('./');
             }
             else
             {
                 echo $this->twig->render('index/index.html.twig', array(
                     'flashMessage' => array(
                         'content'  => 'Inscription échoué',
                         'type'     => 'error',
                     ),
                     'lastUsername' => $username,
                 ));
             }

         }
     }

     /**
      * @Route('/logout', name='logout')
      * @Method('GET')
      */
     public function logout()
     {
         if (isset($_SESSION['user']))
         {
             unset($_SESSION['user']);
             session_destroy();
             $this->session->flash->setFlashMessage('Déconnexion réussie', 'success');

             $this->router->redirect('./');
         }
     }
}

?>
