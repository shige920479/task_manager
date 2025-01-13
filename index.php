<?php
require_once './vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$routes = [
  '/task_manager/' => 'MemberLogin',
  '/task_manager/dashboard/' => 'MemberController',
  '/task_manager/managerLogin/' => 'ManagerLogin',
  '/task_manager/manager_dashboard/' => 'ManagerController',
  '/task_manager/account/' => 'MemberAccount',
  '/task_manager/error/' => 'ErrorController'
  // '/portfolio/task_manager/' => 'MemberLogin',
  // '/portfolio/task_manager/dashboard/' => 'MemberController',
  // '/portfolio/task_manager/managerLogin/' => 'ManagerLogin',
  // '/portfolio/task_manager/manager_dashboard/' => 'ManagerController',
  // '/portfolio/task_manager/account/' => 'MemberAccount',
  // '/portfolio/task_manager/error/' => 'ErrorController'
];

$path = parse_url($_SERVER['REQUEST_URI'] , PHP_URL_PATH);

if(isset($routes[$path])) {
  $controller_name = $routes[$path];
  require "app/Controller/{$controller_name}.php";
} else {
  http_response_code(404);
  echo 'page_not_found';
}