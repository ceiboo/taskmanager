<?php

namespace APP\Controllers;

use JLA\Controller;
use JLA\Render;
use JLA\Route;
use JLA\Session;
use APP\Models\Task;


class TaskController extends Controller
{

    public function __construct($route_params)
    {
        parent::__construct($route_params);
    }

    public function indexAction()
    {
        //var_dump($this->route_params);
        $task = new Task();
        if(isset($this->route_params['status']) && \in_array($this->route_params['status'],Task::$statusValues)) {
            $task->where('status','=',$this->route_params['status'])->find();
        } else {
            $task->find();
        }
        $tasks = $task->fetchAll();
        Render::viewTwig('Tasks/content.html', array('user' => $this->user, 'tasks' => $tasks));
    }

    public function editAction()
    {
        $task = new Task();
        if(isset($this->route_params['id'])) {
            $task->where('id','=',$this->route_params['id'])->find();
        }
        $task = $task->fetch();
        Session::set('csrf', bin2hex(random_bytes(35)));
        Render::viewTwig('Tasks/edit.html', array('user' => $this->user, 'task' => $task, 'csrf' => Session::get('csrf')));

    }

    public function deleteAction()
    {
        $task = new Task();
        if(isset($this->route_params['id'])) {
            $task->where('id','=',$this->route_params['id'])->delete();
        }
        Route::redirect('/tasks');
    }

    public function saveAction()
    {
        $csrf = filter_input(INPUT_POST, 'csrf', FILTER_SANITIZE_STRING);

        if (!$csrf || $csrf !== Session::get('csrf')) {
            throw new \Exception("Method Not Allowed");
        }

        $task = new Task();
        if($_POST['id'] !== '' ) {
            $task->id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
        } else {
            $task->user_id = $this->user->id;
        }
        $task->title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $task->status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
        $task->save();

        Route::redirect('/tasks');
    }
}
