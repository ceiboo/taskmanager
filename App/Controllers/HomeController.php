<?php

namespace APP\Controllers;

use JLA\Controller;
use JLA\Render;
use JLA\Route;
use APP\Models\Task;

class HomeController extends Controller
{

    public function __construct($route_params)
    {
        parent::__construct($route_params);
    }

    public function loginAction()
    {
        Render::viewTwig('login.html');
    }

    public function indexAction()
    {
        $tasks = array_map(function($status) {
            $task = new Task();
            $task->where('status','=',$status)->find();
            return array('status'=>$status, 'total' => $task->rowCount());
        }, Task::$statusValues);

        $task = new Task();
        $task->find();
        $tasks[]=array('status'=>'Tareas','total' => $task->rowCount());

        Render::viewTwig('Index/content.html', array('user' => $this->user, 'tasks' => $tasks));
    }

    public function checkloginAction()
    {
        $this->user = $this->auth->login($_POST['email'],$_POST['password']);
        Route::redirect('/');
    }

    public function logout()
    {
        $this->auth->logout();
        Route::redirect('/');
    }
}
