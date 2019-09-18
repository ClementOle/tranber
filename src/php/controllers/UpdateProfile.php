<?php

namespace tranber\controllers;

use tranber\structures\Controller;
use tranber\views\UpdateProfile as UpdateProfileView;
use tranber\services\Client;

use tranber\functions as fn;


class UpdateProfile extends Controller implements UpdateProfileInterface
{
    public function run()
    {
        $client = Client::getInstance();
        $users = $client->getUser();

        $errors = [];

        if ($model = $this->app->getModel('Users')) {
            $login = $_POST['login'] ?? null;
            $email = $_POST['email'] ?? null;
            $id = $users['id'] ?? null;
            if ($id && $login && $email) {
                if($users['login'] != $login) {
                    $model->loginExists($login);
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Veuillez entrer une adresse email valide';
                }

                if ($users['login'] != $login && $model->loginExists($login)) {
                    $errors[] = 'Cet identifiant existe déjà';
                }

                if ($users['email'] != $email && $model->emailExists($email)) {
                    $errors[] = 'Cet email existe déjà';
                }
                $result = $model->updateUser($id, $login, $email);
                if (empty($errors) && $result) {
                    $_SESSION['user']['login'] =$login;
                    $_SESSION['user']['email'] = $email;

                    $conf    = $this->app->getConf();
                    $data    = $conf->getData();
                    $siteUrl = $data['site-url'];
                    header('Location: ' . $siteUrl);
                } else {
                    foreach ($errors as $error) {
                        echo fn\htmlError($error);
                    }
                }

            }
        }
        $view = new UpdateProfileView($users['id'], $users['login'], $users['email']);
        $view->stringify();
    }
}
