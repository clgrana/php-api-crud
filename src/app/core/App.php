<?php


class App
{
    protected $uri;
    protected $verb;
    protected $post;
    protected $controller = 'user';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->verb = $_SERVER['REQUEST_METHOD'];
        $this->parseUrl();
        $this->run($this->controller, $this->method, $this->params, $this->verb);
    }

    public function parseUrl()
    {
        $urlParams = explode('/', filter_var(rtrim($this->uri, '/'), FILTER_SANITIZE_URL));
        $this->controller = isset($urlParams[1]) ? $urlParams[1] : $this->controller;
        $this->method = isset($urlParams[2]) ? $urlParams[2] : $this->method;
        unset($urlParams[0], $urlParams[1], $urlParams[2]);
        if (count($urlParams) > 0) {
            foreach ($urlParams as $param) {
                $this->params[] = $param;
            }
        }
    }

    public function run($controller, $method, $params, $verb = 'GET')
    {
        if($verb != 'GET'){
            $this->post = $_POST;
        }
        $file = realpath(dirname(__FILE__) . "/../controller/" . $controller . ".php");
        if (file_exists($file)) {
            require_once($file);
            $contr = new $controller();
            $contr->$method($params, $this->post);
        } else {
            exit('controller not found');
        }
    }
}
