<?php

require dirname(__DIR__) . '/vendor/autoload.php';


error_reporting(E_ALL);
set_error_handler('JLA\Error::errorHandler');
set_exception_handler('JLA\Error::exceptionHandler');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

JLA\Session::start();
JLA\Auth::start('APP\\Models\\User', 'login');

require dirname(__DIR__) . '/App/Config/Routes/route.php';
