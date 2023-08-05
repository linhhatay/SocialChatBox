<?php

use App\App;
use App\Config;
use App\Container;
use App\Controllers\AuthController;
use App\Router;
use App\Controllers\HomeController;

require_once __DIR__ . './vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$container = new Container();
$router  = new Router($container);


$web_root = $_SERVER['SCRIPT_NAME'];
$web_root = dirname($web_root);
$web_root = str_replace('\\', '/', $web_root);

define('_WEB_ROOT', $web_root);
define('STORAGE_PATH', __DIR__ . '/storage');
define('VIEW_PATH', __DIR__ . '/views');
define('RESOURCES_PATH', __DIR__ . '/resources');

$router
    ->get(
        _WEB_ROOT,
        [HomeController::class, 'index']
    );

// Route login
$router
    ->get(
        _WEB_ROOT . '/tai-khoan',
        [AuthController::class, 'index']
    )->post(
        _WEB_ROOT . '/tai-khoan',
        [AuthController::class, 'login']
    );

// echo '<pre>';
// var_dump($router->routes());
// echo '</pre>';

(new App(
    $router,
    ['uri' => $_SERVER['REQUEST_URI'], 'method' => $_POST['_method'] ?? $_SERVER['REQUEST_METHOD']],
    new Config($_ENV)
))->run();
