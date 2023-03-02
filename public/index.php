<?php

use app\controllers\HomeController;
use app\controllers\RegisterController;
use core\Application;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Application(dirname(__DIR__));

$app->router->get('/', [HomeController::class, 'index']);
$app->router->get('/register', [RegisterController::class, 'register']);
$app->router->post('/register', [RegisterController::class, 'register']);



$app->run();