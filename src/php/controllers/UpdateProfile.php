<?php

namespace tranber\controllers;

use tranber\structures\Controller;
use tranber\views\UpdateProfile as UpdateProfileView;
use tranber\services\Client;


class UpdateProfile extends Controller implements UpdateProfileInterface
{
    public function run()
    {
        $client = Client::getInstance();
        $users = $client->getUser();

        if ($model = $this->app->getModel('Users')) {
            $login = $_POST['login'] ?? null;
            $email = $_POST['email'] ?? null;
            $user = $_SESSION['user'];
            $id = $user['id'] ?? null;
            if ($id && $login && $email) {
                $result = $model->updateUser($id, $login, $email);
                if($result) {
                    $user['login'] =$login;
                    $user['email'] = $email;
                    $_SESSION['user'] = $user;

                    $conf    = $this->app->getConf();
                    $data    = $conf->getData();
                    $siteUrl = $data['site-url'];
                    header('Location: ' . $siteUrl);
                }
            }

        }
        $view = new UpdateProfileView($users['id'], $users['login'], $users['email']);
        $view->stringify();
    }
}
