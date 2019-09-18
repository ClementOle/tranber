<?php

namespace tranber\views;

use tranber\structures\View;

class UpdateProfile extends View
{
    public function __construct($id, $login, $email, $avatar)
    {
        $this->setTemplate('HtmlHeader');
        $this->setTemplate('Navbar');
        $this->setTemplate('UpdateProfile', ['login' => $login,
        'email' => $email, 'id' => $id, 'avatar' => $avatar]);
        $this->setTemplate('HtmlFooter');
    }
}
