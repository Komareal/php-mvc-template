<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
use Core\Router;

require_once __DIR__ . '/../vendor/autoload.php';

$router = Router::get();
$router->loadPage($_SERVER['REQUEST_URI']);