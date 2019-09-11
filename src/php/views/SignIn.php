<?php

namespace tranber\views;

use tranber\structures\View;

class SignIn extends View
{
	public function __construct()
	{
		$this->setTemplate('HtmlHeader');
		$this->setTemplate('Navbar');
		$this->setTemplate('SignIn');
		$this->setTemplate('HtmlFooter');
	}
}