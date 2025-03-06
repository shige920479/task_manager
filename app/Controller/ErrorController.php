<?php
require_once './vendor/autoload.php';
require_once './app/config/config.php';
require_once './app/Services/helper.php';

use App\Services\GetRequest;
use function App\Services\flash;

session_start();
$request = GetRequest::getRequest();

if($request['error_mode'] === '500error') {
  $flash_array = "";
  if(isset($_SESSION['error'])) $flash_array = flash($_SESSION['error']);
  include('./app/Views/500error.php');
  exit;
} elseif($request['error_mode'] === '400error') {
  $flash_array = "";
  if(isset($_SESSION['error'])) $flash_array = flash($_SESSION['error']);
  include('./app/Views/400error.php');
  exit;
}
