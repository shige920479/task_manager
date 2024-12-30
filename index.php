<?php
$routes = [
  '/task_manager/' => 'MemberLogin',
  '/task_manager/dashboard/' => 'MemberController',
  '/task_manager/managerLogin/' => 'ManagerLogin',
  '/task_manager/manager_dashboard/' => 'ManagerController',
  '/task_manager/account/' => 'MemberAccount'
];

$path = parse_url($_SERVER['REQUEST_URI'] , PHP_URL_PATH);

if(isset($routes[$path])) {
  $controller_name = $routes[$path];
  require "app/Controller/{$controller_name}.php";
} else {
  http_response_code(404);
  echo 'page_not_found';
}