<?php

namespace tranber\controllers;

use tranber\structures\Controller;
use tranber\views\SignIn as SignInView;
use tranber\functions as fn;
use tranber\services\Client;

class SignIn extends Controller implements SignInInterface
{
	public function run()
	{
		if ($model = $this->app->getModel('Users')) {

			$login    = $_POST['login']    ?? null;
			$password = $_POST['password'] ?? null;

			if ($login && $password) {
				$user = $model->logIn($login, $password);

				if ($user) {
					//Connection
					$client = Client::getInstance();
					$client->logIn($user);

					//Redirection
					$conf    = $this->app->getConf();
					$data    = $conf->getData();
					$siteUrl = $data['site-url'];
					header('Location: ' . $siteUrl);
				} else {
					echo fn\htmlError('Identifiant et/ou mot de passe invalide(s)');
				}
			}
		}



		$view = new SignInView;
		$view->stringify();
	}
}
