<?php

$route = new JLA\Route();

$route->add('/', ['controller' => 'HomeController', 'action' => 'index']);


$route->add('/login', ['controller' => 'LoginController', 'action' => 'login']);
$route->add('/checklogin', ['controller' => 'LoginController', 'action' => 'checklogin']);
$route->add('/logout', ['controller' => 'LoginController', 'action' => 'logout']);

$route->add('/tasks', ['controller' => 'TaskController', 'action' => 'index']);
$route->add('/tasks/{status}', ['controller' => 'TaskController', 'action' => 'index']);

$route->add('/task/add', ['controller' => 'TaskController', 'action' => 'edit']);
$route->add('/task/delete/{id}', ['controller' => 'TaskController', 'action' => 'delete']);
$route->add('/task/edit/{id}', ['controller' => 'TaskController', 'action' => 'edit']);

$route->add('/task/save', ['controller' => 'TaskController', 'action' => 'save']);
//$route->add('{controller}/{action}');

$route->run($_SERVER['REQUEST_URI']);
