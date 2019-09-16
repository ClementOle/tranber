<?php

namespace tranber\controllers;

use tranber\structures\Controller;
use tranber\services\Client;

class SignOut extends Controller implements SignOutInterface
{
	public function run()
	{
		$client = Client::getInstance();
		$client->logOut();

		//Redirection
		$conf    = $this->app->getConf();
		$data    = $conf->getData();
		$siteUrl = $data['site-url'];
		header('Location: ' . $siteUrl);
	}
}
