<?php

namespace JLA;

class Error
{

    public static function errorHandler($level, $message, $file, $line)
    {
        if (error_reporting() !== 0) {  // to keep the @ operator working
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    public static function exceptionHandler($exception)
    {
        $code = $exception->getCode();
        if ($code != 404) {
            $code = 500;
        }
        http_response_code($code);

        $message = '';

        if ($_ENV['APP_ENV']=='dev') {
            $message.="<p>Uncaught exception: '" . get_class($exception) . "'</p>";
            $message.="<p>Message: '" . $exception->getMessage() . "'</p>";
            $message.="<p>Stack trace:<pre>" . $exception->getTraceAsString() . "</pre></p>";
            $message.="<p>Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";
            echo $message;
            //Render::viewTwig("404.html",array('message',$exception->getMessage()));
        } else {
            $log = dirname(__DIR__) . '/logs/' . date('Y-m-d') . '.txt';
            ini_set('error_log', $log);

            $message .= "Uncaught exception: '" . get_class($exception) . "'";
            $message .= " with message '" . $exception->getMessage() . "'";
            $message .= "\nStack trace: " . $exception->getTraceAsString();
            $message .= "\nThrown in '" . $exception->getFile() . "' on line " . $exception->getLine();

            error_log($message);

            Render::viewTwig("404.html",array('message',$exception->getMessage()));
        }
    }
}
