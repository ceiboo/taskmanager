<?php

namespace JLA;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Render
{

    public static function view($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = $_ENV['BASE_PATH']. "/App/Views/$view";  // relative to Core directory

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }

    public static function viewTwig($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new FilesystemLoader($_ENV['BASE_PATH'] . '/App/Views');
            $twig = new Environment($loader);
            $twig->addGlobal('Env', $_ENV);
            $twig->addGlobal('DOMAIN', $_ENV['DOMAIN']);
            $twig->addGlobal('IMAGE_PATH', $_ENV['DOMAIN'] . '/Views/Assets/img/');
        }

        echo $twig->render($template, $args);
    }
}
