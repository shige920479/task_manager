<?php
require_once './vendor/autoload.php';
require_once './app/Services/helper.php';
require_once './app/config/config.php';

use App\Database\Login;
use App\Services\GetRequest;

use function App\Services\flash;
use function App\Services\old;

session_start();

if(isset($_SESSION['m_login'])) {
  header('Location:' . PATH . 'manager_dashboard?mode=index');
  exit;
}

$request = GetRequest::getRequest();

if(!empty($request) && $request['mode'] === 'login') {
  Login::Login($request, MANAGER);
} else {
  list($flash_array, $old) = ["", ""];
  if(isset($_SESSION['error'])) $flash_array = flash($_SESSION['error']);
  if(isset($_SESSION['old'])) $old = old($_SESSION['old']);  

  include('./app/Views/ManagerLoginView.php');
  exit;
}