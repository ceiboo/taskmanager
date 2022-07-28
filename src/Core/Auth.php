<?php

namespace JLA;

use JLA\Session;

class Auth
{
    private static $authClass;
    private static $loginRoute;

    public static function start(string $authClass, string $loginRoute)
    {
        self::$authClass = $authClass;
        self::$loginRoute = $loginRoute;
    }

    public static function loginRoute()
    {
        return self::$loginRoute;
    }

    public static function getUser()
    {
        return Session::get('user');
    }

    public static function login(string $email, string $password)
    {
        $users = new self::$authClass();
        $users->where('email', '=', $email)->where('password', '=', MD5($password))->limit(1)->find();
        $user = $users->fetch();

        if(!isset($user->email)) {
              return null;
        }
        Session::set('user',$user);
        return $user;
    }

    public function isLogged()
    {
        return Session::isset('user');
    }

    public function logout()
    {
        Session::unset('user');
    }
}
