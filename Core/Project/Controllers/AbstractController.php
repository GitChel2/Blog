<?php

namespace Project\Controllers;

use Project\Models\Users\UsersAuthService;
use Project\View\View;
use Project\Models\Users\User;

abstract class AbstractController
{
    /** @var View */
    protected View $view;

    /** @var User|null */
    protected $user;

    public function __construct()
    {
        $this->view = new View(__DIR__ . '/../../../templates');
        $this->user = UsersAuthService::getUserByToken();
        $this->view->setVar('user', $this->user);
    }

}