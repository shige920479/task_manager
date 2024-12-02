<?php
require_once '../../vendor/autoload.php';
require_once '../Services/helper.php';
require_once '../config/config.php';

use App\Services\GetForm;
use App\Database\Login;
use function App\Services\flash;
use function App\Services\old;

session_start();
$in = GetForm::getForm();

if(!empty($in) && $in['mode'] === 'login') {
  Login::Login($in, MANAGER);
} else {
  list($flash_array, $old) = ["", ""];
  if(isset($_SESSION['error'])) $flash_array = flash($_SESSION['error']);
  if(isset($_SESSION['old'])) $old = old($_SESSION['old']);  

  include('../Views/ManagerLoginView.php');
  exit;
}