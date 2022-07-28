<?php

namespace APP\Controllers;

use JLA\Controller;
use JLA\Render;
use JLA\Route;

class LoginController extends Controller
{

    public function __construct($route_params)
    {
        parent::__construct($route_params, true);
    }

    public function loginAction()
    {
        Render::viewTwig('login.html');
    }

    public function checkloginAction()
    {
        $this->user = $this->auth->login($_POST['email'],$_POST['password']);
        if($this->user === null)
        {
            Route::redirect('/login');
        }
        Route::redirect('/');

    }

    public function logout()
    {
        $this->auth->logout();
        Route::redirect('/login');
    }
}
