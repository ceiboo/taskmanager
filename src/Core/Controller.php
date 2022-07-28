<?php

namespace JLA;

use JLA\Auth;

abstract class Controller
{

    protected $route_params = [];

    public $auth;
    public $user;

    public function __construct($route_params)
    {
        $this->auth = new Auth();
        $this->user = $this->auth->getUser();
        $this->route_params = $route_params;
    }

    public function __call($name, $args)
    {

        if(!$this->auth->isLogged() && $name!==$this->auth->loginRoute()) {
            Route::redirect('/'.$this->auth->loginRoute());
        }

        $method = $name . 'Action';

        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            throw new \Exception("Method $method not found in controller " . get_class($this));
        }
    }

    protected function before()
    {
    }

    protected function after()
    {
    }


}
