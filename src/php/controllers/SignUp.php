<?php

namespace tranber\controllers;

use tranber\structures\Controller;
use tranber\views\SignUp as SignUpView;


use tranber\functions as fn;

class SignUp extends Controller implements SignUpInterface
{
	public function run()
	{
		$errors   = [];

		if ($model = $this->app->getModel('Users')) {

			$login    = $_POST['login']    ?? null;
			$password = $_POST['password'] ?? null;
			$email    = $_POST['email']    ?? null;

			if ($login && $password && $email) {
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$errors[] = 'Veuillez entrer une adresse email valide';
				}

				if ($model->loginExists($login)) {
					$errors[] = 'Cet identifiant existe déjà';
				}

				if ($model->emailExists($email)) {
					$errors[] = 'Cet email existe déjà';
				}

				if (empty($errors) && $model->createUser($login, $password, $email)) {
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

		$view = new SignUpView;
		$view->stringify();
	}
}
