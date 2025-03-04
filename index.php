<?php
declare(strict_types=1);
require_once './vendor/autoload.php';
require_once './app/config/config.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$routes = [
  PATH => 'MemberLogin',
  PATH . 'dashboard/' => 'MemberController',
  PATH . 'managerLogin/' => 'ManagerLogin',
  PATH . 'manager_dashboard/' => 'ManagerController',
  PATH . 'account/' => 'MemberAccount',
  PATH . 'error/' => 'ErrorController'
];

$path = parse_url($_SERVER['REQUEST_URI'] , PHP_URL_PATH);

if(isset($routes[$path])) {
  $controller_name = $routes[$path];
  require "app/Controller/{$controller_name}.php";
} else {
  http_response_code(404);
  echo 'page_not_found';
}