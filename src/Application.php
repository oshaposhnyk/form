<?php

namespace core;

use app\src\helpers\JsonFileHandler;
use app\src\Session;

class Application
{
    public static Application $app;
    public Controller $controller;
    public Router $router;
    public Request $request;
    public Response $response;
    public static string $ROOT_DIR;
    public Session $session;


    public function __construct(string $rootDir)
    {
        self::$ROOT_DIR = $rootDir;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->session = new Session();
    }

    public function run()
    {
        echo $this->router->resolve();
    }

}