<?php

namespace tranber\controllers;

use tranber\services\Client;
use tranber\structures\Controller;
use tranber\views\UpdateProfile as UpdateProfileView;

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
            $avatarName = $users['avatarName'];

            if ($id && $login && $email) {
                if ($users['login'] != $login) {
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

                if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
                    $avatar = $_FILES['avatar'];
                    // Testons si le fichier n'est pas trop gros
                    if ($avatar['size'] <= 100000000000) {
                        // Testons si l'extension est autorisée
                        $infosfichier = pathinfo($avatar['name']);
                        $extension_upload = $infosfichier['extension'];
                        $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                        if (in_array($extension_upload, $extensions_autorisees)) {
                            // On peut valider le fichier et le stocker définitivement
                            move_uploaded_file($avatar['tmp_name'], 'uploads/' . $id. basename($avatar['name']));
                            $avatarName = $id . $avatar['name'];
                            echo "L'envoi a bien été effectué !";

                        } else {
                            $errors[] = "Les extensions de fichiers autorisés sont : jpg, jpeg, gif, png ";
                        }
                    } else {
                        $errors[] = "Le fichier est trop gros";
                    }
                }
                $result = $model->updateUser($id, $login, $email, $avatarName);

                if (empty($errors) && $result) {
                    $_SESSION['user']['login'] = $login;
                    $_SESSION['user']['email'] = $email;
                    $_SESSION['user']['avatarName'] = $avatarName;
                    $conf = $this->app->getConf();
                    $data = $conf->getData();
                    $siteUrl = $data['site-url'];
                    header('Location: ' . $siteUrl);
                } else {
                    foreach ($errors as $error) {
                        echo fn\htmlError($error);
                    }
                }
            }


        }
        $view = new UpdateProfileView($users['id'], $users['login'], $users['email'], 'uploads/' . $users['avatarName']);
        $view->stringify();
    }
}
